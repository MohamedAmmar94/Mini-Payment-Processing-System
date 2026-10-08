<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {
    use SoftDeletes, HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'issue_date'      => 'date',
        'due_date'        => 'date',
        'subtotal'        => 'decimal:4',
        'tax_amount'      => 'decimal:4',
        'discount_amount' => 'decimal:4',
        'total'           => 'decimal:4',
        'paid_amount'     => 'decimal:4',
    ];
    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function items() {
        return $this->hasMany(InvoiceItem::class);
    }
    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
