<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model {
    use SoftDeletes, HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'quantity'        => 'decimal:4',
        'unit_price'      => 'decimal:4',
        'discount_amount' => 'decimal:4',
        'tax_amount'      => 'decimal:4',
        'total'           => 'decimal:4',
    ];
    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }
}
