<?php
namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentWebhookEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ProcessTapWebhook implements ShouldQueue {
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $webhookEventId
    ) {
    }

    public function handle() {
        Log::info('Tap Webhook started processing Job ID : ' . $this->webhookEventId);
        DB::transaction(function () {
            /*
             * 1. Lock the webhook event
             */
            $event = PaymentWebhookEvent::whereKey(
                $this->webhookEventId
            )
                ->lockForUpdate()
                ->firstOrFail();

            /*
             * 2. Idempotency:
             *    If this event was already processed,
             *    do nothing.
             */
            if ($event->processed_at) {
                return;
            }

            /*
             * 3. Lock the payment
             */
            $payment = Payment::whereKey(
                $event->payment_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $payload = $event->payload;

            /*
             * 4. Validate important webhook data
             */
            $this->validatePayload($payment, $payload);
            /*
             * 5. Handle payment status
             */
            if ($payload['status'] === 'CAPTURED') {

                /*
                 * Payment is already paid.
                 * This protects us if another path already
                 * changed the payment state.
                 */
                if ($payment->status === 'paid') {
                    $event->update([
                        'processed_at' => now(),
                    ]);

                    return;
                }

                /*
                 * Payment should normally be processing here.
                 */
                if ($payment->status !== 'processing') {
                    throw new RuntimeException(
                        "Payment {$payment->id} cannot be marked as paid. Status {$payment->status} . "
                    );
                }

                /*
                 * 6. Lock invoice
                 */
                $invoice = Invoice::whereKey(
                    $payment->invoice_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                 * 7. Update payment
                 */
                $payment->update([
                    'status'                  => 'paid',
                    'provider_transaction_id' => $payload['id'],
                    'paid_at'                 => now(),
                ]);

                /*
                 * 8. Update invoice
                 */
                $invoice->paid_amount += $payment->amount;

                if ($invoice->paid_amount >= $invoice->total) {
                    $invoice->paid_amount = $invoice->total;
                    $invoice->status      = 'paid';
                } else {
                    $invoice->status = 'partially_paid';
                }

                $invoice->save();
            }

            /*
             * 9. Mark webhook as processed
             */
            $event->update([
                'processed_at' => now(),
            ]);
        });
    }
    private function validatePayload(
        Payment $payment,
        array $payload
    ): void {
        /*
         * Make sure this webhook belongs to
         * the same payment.
         */
        $paymentUuid =
        $payload['reference']['transaction'] ?? null;

        if ($paymentUuid !== $payment->uuid) {
            throw new RuntimeException(
                'Webhook payment reference does not match payment.'
            );
        }

        /*
         * Make sure amount matches.
         */
        $webhookAmount = (string) $payload['amount'];

        if (bccomp(
            $webhookAmount,
            (string) $payment->amount,
            4
        ) !== 0) {
            throw new RuntimeException(
                'Webhook amount does not match payment amount.'
            );
        }

        /*
         * Make sure currency matches.
         */
        if (
            strtoupper($payload['currency'])
            !== strtoupper($payment->currency)
        ) {
            throw new RuntimeException(
                'Webhook currency does not match payment currency.'
            );
        }
    }
}
