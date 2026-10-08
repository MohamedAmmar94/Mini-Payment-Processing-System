<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('payment_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id', 150)->unique();

            $table->string('event_type', 100)->nullable();

            $table->string('provider', 50);

            $table->foreignId('payment_id')
                ->nullable()
                ->constrained('payments')
                ->nullOnDelete();

            $table->jsonb('payload');

            $table->string('signature')->nullable();

            $table->timestamp('processed_at')->nullable();

            $table->text('processing_error')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->index([
                'provider',
                'event_type',
            ]);

            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('payment_webhook_events');
    }
};
