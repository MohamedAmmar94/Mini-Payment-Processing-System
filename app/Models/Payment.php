<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model {
    use SoftDeletes, HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        // 'status'   => PaymentStatus::class,
        'amount'   => 'decimal:4',
        'paid_at'  => 'datetime',
        'metadata' => 'array',
    ];
    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }
    public function webhookEvents() {
        return $this->hasMany(PaymentWebhookEvent::class);
    }
}
