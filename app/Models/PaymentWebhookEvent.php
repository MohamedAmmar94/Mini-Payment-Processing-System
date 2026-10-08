<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentWebhookEvent extends Model {
    use SoftDeletes;
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'payload' => 'array',
    ];
    public function payment() {
        return $this->belongsTo(Payment::class);
    }
}
