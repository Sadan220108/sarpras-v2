<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // pemohon | koordinator | kepala_bauk | asisten_direktur | admin
            $table->string('role')->default('pemohon')->after('password');
            $table->string('jabatan')->nullable()->after('role');
            $table->string('unit')->nullable()->after('jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'jabatan', 'unit']);
        });
    }
};
