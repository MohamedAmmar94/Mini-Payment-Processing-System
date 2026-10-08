<?php
namespace App\Models;

use App\Models\JournalEntryLine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model {
    use SoftDeletes, HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function parent() {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function journalEntryLines() {
        return $this->hasMany(JournalEntryLine::class);
    }
}
