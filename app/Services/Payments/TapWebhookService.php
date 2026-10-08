<?php
namespace App\Services\Payments;

use App\Models\Payment;
use App\Models\PaymentWebhookEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TapWebhookService {
    public function verifySignature(
        array $payload,
        string $receivedHash
    ): bool {
        $amount = $this->formatAmount(
            $payload['amount'],
            $payload['currency']
        );

        $id = $payload['id'] ?? '';

        $currency = $payload['currency'] ?? '';

        $gatewayReference =
        $payload['reference']['gateway'] ?? '';

        $paymentReference =
        $payload['reference']['payment'] ?? '';

        $status = $payload['status'] ?? '';

        $created =
        $payload['transaction']['created'] ?? '';

        $toBeHashed =
            'x_id' . $id .
            'x_amount' . $amount .
            'x_currency' . $currency .
            'x_gateway_reference' . $gatewayReference .
            'x_payment_reference' . $paymentReference .
            'x_status' . $status .
            'x_created' . $created;

        $computedHash = hash_hmac(
            'sha256',
            $toBeHashed,
            config('services.tap.secret_key')
        );

        return hash_equals(
            $computedHash,
            $receivedHash
        );
    }

    public function receive(array $payload, $hashString): array {
        $arr = DB::transaction(function () use ($payload, $hashString) {

            $eventId = $this->extractEventId($payload);

            $paymentUuid =
            $payload['reference']['transaction'] ?? null;

            if (! $paymentUuid) {
                Log::info('Payment UUID not found in Tap webhook.');
                throw new RuntimeException(
                    'Payment UUID not found in Tap webhook.'
                );
            }

            $payment = Payment::where(
                'uuid',
                $paymentUuid
            )->firstOrFail();
            $event = PaymentWebhookEvent::firstOrCreate(
                [
                    'event_id' => $eventId,
                ],
                [
                    'event_type' => $payload['status'],
                    'provider'   => 'tap',
                    'payment_id' => $payment->id,
                    'payload'    => $payload,
                    'signature'  => $hashString,
                ]
            );
            return ['is_new' => $event->wasRecentlyCreated, 'event' => $event];
        });
        return $arr;
    }

    private function extractEventId(array $payload): string {
        $chargeId = $payload['id'] ?? null;
        $status   = $payload['status'] ?? null;

        if (! $chargeId || ! $status) {
            throw new RuntimeException(
                'Invalid Tap webhook payload.'
            );
        }

        return "tap:{$chargeId}:{$status}";
    }

    private function formatAmount(
        float | string $amount,
        string $currency
    ): string {
        $decimals = match ($currency) {
            'BHD',
            'JOD',
            'KWD',
            'OMR'   => 3,

            default => 2,
        };

        return number_format(
            (float) $amount,
            $decimals,
            '.',
            ''
        );
    }
}