<?php
namespace App;

enum PaymentStatus: string {
case PENDING    = 'pending';
case PROCESSING = 'processing';
case PAID       = 'paid';
case FAILED     = 'failed';
case REFUNDED   = 'refunded';
case CANCELLED  = 'cancelled';
}
