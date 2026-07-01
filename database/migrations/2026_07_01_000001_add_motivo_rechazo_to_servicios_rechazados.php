<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('empresas_servicio_rechazadas') && !Schema::hasColumn('empresas_servicio_rechazadas', 'motivo_rechazo')) {
            Schema::table('empresas_servicio_rechazadas', function (Blueprint $table) {
                $table->text('motivo_rechazo')->nullable();
            });
        }

        if (Schema::hasTable('servicios_rechazados') && !Schema::hasColumn('servicios_rechazados', 'motivo_rechazo')) {
            Schema::table('servicios_rechazados', function (Blueprint $table) {
                $table->text('motivo_rechazo')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('empresas_servicio_rechazadas') && Schema::hasColumn('empresas_servicio_rechazadas', 'motivo_rechazo')) {
            Schema::table('empresas_servicio_rechazadas', function (Blueprint $table) {
                $table->dropColumn('motivo_rechazo');
            });
        }

        if (Schema::hasTable('servicios_rechazados') && Schema::hasColumn('servicios_rechazados', 'motivo_rechazo')) {
            Schema::table('servicios_rechazados', function (Blueprint $table) {
                $table->dropColumn('motivo_rechazo');
            });
        }
    }
};
