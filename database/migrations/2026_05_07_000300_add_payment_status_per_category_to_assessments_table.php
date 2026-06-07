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
        Schema::table('assessments', function (Blueprint $table) {
            $table->timestamp('fitrah_paid_at')->nullable()->after('nominal_zakat_fitrah');
            $table->timestamp('maal_paid_at')->nullable()->after('fitrah_paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn(['fitrah_paid_at', 'maal_paid_at']);
        });
    }
};
