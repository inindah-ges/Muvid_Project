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
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->text('notes')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('payment_method', [
                // Offline Payment
                'cash', // Pembayaran di kasir
                // E-Wallet
                'gopay', // GoPay
                'shopeepay', // ShopeePay
                'qris', // QRIS (OVO, DANA, LinkAja, dll)
                // Bank Transfer (Virtual Account)
                'bca_va', // BCA Virtual Account
                'bni_va', // BNI Virtual Account
                'bri_va', // BRI Virtual Account
                'mandiri_va', // Mandiri Virtual Account
                'permata_va', // Permata Virtual Account
                'cimb_va', // CIMB Niaga Virtual Account
                'bsi_va', // BSI Virtual Account
                'danamon_va', // Danamon Virtual Account
                // Kartu Kredit/Debit
                'credit_card', // Visa, Mastercard, JCB, AMEX
                // Convenience Store
                'indomaret', // Indomaret
                'alfamart', // Alfamart
                // Cardless Credit
                'akulaku', // Akulaku PayLater
                'kredivo', // Kredivo
                'gopay_later', // GoPayLater
                'shopeepay_later', // SPayLater
            ])->nullable();
            $table->decimal('payment_amount', 10, 2);
            $table->string('order_number')->unique();
            $table->enum('order_type', ['dine_in', 'takeaway'])->default('dine_in');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_orders');
    }
};
