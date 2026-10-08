<?php
namespace App\Services;
use App\Contracts\PaymentGatewayInterface;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentService {
    /**
     * Create a new class instance.
     */
    public function __construct(
        private PaymentGatewayInterface $gateway
    ) {
    }
    public function createPayment(
        Invoice $invoice,
        array $data,
        string $idempotencyKey
    ): Payment {
        [$payment, $isNew] = DB::transaction(function () use (
            $invoice,
            $data,
            $idempotencyKey
        ) {
            // 1. Idempotency check
            $existingPayment = Payment::where(
                'idempotency_key',
                $idempotencyKey
            )->first();

            if ($existingPayment) {
                return [$existingPayment, false];
            }

            // 2. Lock invoice
            $invoice = Invoice::whereKey($invoice->id)
                ->lockForUpdate()
                ->first();

            // 3. Check invoice state
            if (
                in_array($invoice->status, [
                    'draft',
                    'cancelled',
                    'paid',
                ])
            ) {
                throw new RuntimeException(
                    'Invoice cannot accept payment.'
                );
            }

            // 4. Calculate remaining amount
            $remainingAmount =
            $invoice->total - $invoice->paid_amount;

            // 5. Validate payment amount
            if ($data['amount'] > $remainingAmount) {
                throw new RuntimeException(
                    'Payment amount exceeds remaining balance.'
                );
            }

            // 6. Create payment
            $payment = Payment::create([
                'uuid'            => (string) Str::uuid(),
                'invoice_id'      => $invoice->id,
                'amount'          => $data['amount'],
                'currency'        => $data['currency'],
                'status'          => 'processing',
                'payment_method'  =>
                $data['payment_method'],
                'provider'        => 'tap',
                'idempotency_key' =>
                $idempotencyKey,
            ]);
            return [$payment, true];
        });
        if (! $isNew) {
            return $payment;
        }
        // External API call happens AFTER commit.
        $this->sendToGateway($payment);
        return $payment->fresh();
    }

    private function sendToGateway(Payment $payment): void {
        $response =
        $this->gateway->createCharge($payment);

        $payment->update([
            'provider_transaction_id' =>
            $response['id'] ?? null,

            'metadata'                => $response,
        ]);
    }
}
