<?php
namespace App\Contracts;
use App\Models\Payment;

interface PaymentGatewayInterface {
    public function createCharge(Payment $payment): array;
}
