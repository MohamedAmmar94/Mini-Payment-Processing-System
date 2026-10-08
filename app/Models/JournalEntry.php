<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model {
    use SoftDeletes, HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function lines() {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function payment() {
        return $this->belongsTo(Payment::class);
    }
}
