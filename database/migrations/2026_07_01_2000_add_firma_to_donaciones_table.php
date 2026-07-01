<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donaciones', function (Blueprint $table) {
            $table->longText('firma_electronica')->nullable()->after('notas');
            $table->timestamp('firma_fecha')->nullable()->after('firma_electronica');
        });
    }

    public function down(): void
    {
        Schema::table('donaciones', function (Blueprint $table) {
            $table->dropColumn(['firma_electronica', 'firma_fecha']);
        });
    }
};
