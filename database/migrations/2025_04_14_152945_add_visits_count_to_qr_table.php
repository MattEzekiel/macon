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
        Schema::table('_q_r', function (Blueprint $table) {
            $table->unsignedInteger('visits_count')->default(0)->after('url_qrcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('_q_r', function (Blueprint $table) {
            $table->dropColumn('visits_count');
        });
    }
};
