<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_category_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['income', 'expense', 'transfer'])->default('expense');
            $table->foreignId('from_account_id')->constrained('accounts')->onDelete('cascade');
            $table->unsignedBigInteger('to_account_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->foreignId('budget_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('date');
            $table->decimal('amount', 20, 2);
            $table->string('items', 255)->nullable();
            $table->string('place', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'date']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
