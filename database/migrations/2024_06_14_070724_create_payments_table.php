<?php

use App\Enums\PaymentStatusEnum;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('snap_token');
            $table->json('payload');
            $table->json('response')->nullable();
            $table->string('signature')->nullable();
            $table->string('status_code')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->nullable(); // will follow the midtrans payment status such like settlement, failed, etc
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
