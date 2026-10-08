<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * إيه الخانات المالية اللي عند الشركة؟
     */
    public function up(): void {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->enum('type', [
                'asset',     // الأصول (حاجة الشركة تمتلكها ولها قيمة)
                'liability', // الخصوم أو الالتزامات (ديون على الشركة)
                'equity',    // حق أصحاب الشركة في الشركة بعد خصم الالتزامات (رأس المال)
                'revenue',   // الإيرادات (فلوس بتدخل الشركة)
                'expense',   // المصروفات (فلوس بتخرج من الشركة)
            ]);
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('accounts')
                ->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('accounts');
    }
};
