<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * العملية دي أثرت على أنهي Accounts وبكام؟
     */
    public function up(): void {
        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')
                ->constrained('journal_entries')
                ->cascadeOnDelete();

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->restrictOnDelete();

            $table->decimal('debit', 20, 4)->default(0);

            $table->decimal('credit', 20, 4)->default(0);

            $table->string('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'account_id',
                'journal_entry_id',
            ]);
        });
        DB::statement('
            ALTER TABLE journal_entry_lines
            ADD CONSTRAINT journal_entry_lines_debit_credit_check
            CHECK (
                (debit > 0 AND credit = 0)
                OR
                (credit > 0 AND debit = 0)
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('journal_entry_lines');
    }
};
