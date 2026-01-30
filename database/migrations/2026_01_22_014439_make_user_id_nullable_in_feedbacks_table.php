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
        Schema::table('usability_feedbacks', function (Blueprint $table) {
            // Kita ubah user_id jadi nullable
            // Note: Pastikan kolom user_id sudah ada sebelumnya
            $table->foreignId('user_id')->nullable()->change();
            $table->string('guest_name')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usability_feedbacks', function (Blueprint $table) {
            // $table->foreignId('user_id')->nullable(false)->change(); // Kembalikan jika perlu (hati-hati data error)
            $table->dropColumn('guest_name');
        });
    }
};
