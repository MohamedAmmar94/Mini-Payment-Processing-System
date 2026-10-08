<?php
namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class TapPaymentGateway implements PaymentGatewayInterface {
    public function createCharge(Payment $payment): array {
        $invoice  = $payment->invoice;
        $customer = $invoice->customer;

        $response = Http::withToken(
            config('services.tap.secret_key')
        )
            ->acceptJson()
            ->post(
                config('services.tap.base_url') . '/v2/charges/',
                [
                    'amount'             => $payment->amount,

                    'currency'           => $payment->currency,

                    'customer_initiated' => true,

                    'threeDSecure'       => true,

                    'save_card'          => false,

                    'description'        =>
                    "Payment for {$invoice->invoice_number}",

                    'reference' => [
                        'transaction' => $payment->uuid,
                        'order'       => $invoice->uuid,
                        'idempotent'  => $payment->idempotency_key,
                    ],

                    'metadata'  => [
                        'payment_uuid' => $payment->uuid,
                        'invoice_uuid' => $invoice->uuid,
                    ],

                    'customer'  => [
                        'first_name' => $customer->name,
                        'email'      => $customer->email,
                        'phone'      => [
                            'country_code' => '20',
                            'number'       => $customer->phone,
                        ],
                    ],

                    'merchant'  => [
                        'id' => config(
                            'services.tap.merchant_id'
                        ),
                    ],

                    'source'    => [
                        'id' => 'src_all',
                    ],

                    'post'      => [
                        'url' => config(
                            'services.tap.webhook_url'
                        ),
                    ],

                    'redirect'  => [
                        'url' => config(
                            'services.tap.redirect_url'
                        ),
                    ],
                ]
            );

        $response->throw();

        return $response->json();
    }
    public function retrive() {
        $response = Http::withToken(config('services.tap.secret_key'))
            ->acceptJson()
            ->get(
                config('services.tap.base_url') .
                '/v2/charges/chg_TS04A5120261905Px920610521'
            );

        dd($response->json());
    }
}