<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('empresas_producto_rechazadas') && !Schema::hasColumn('empresas_producto_rechazadas', 'motivo_rechazo')) {
            Schema::table('empresas_producto_rechazadas', function (Blueprint $table) {
                $table->text('motivo_rechazo')->nullable()->after('fecha_rechazo');
            });
        }

        if (Schema::hasTable('productos_rechazados') && !Schema::hasColumn('productos_rechazados', 'motivo_rechazo')) {
            Schema::table('productos_rechazados', function (Blueprint $table) {
                $table->text('motivo_rechazo')->nullable()->after('fecha_rechazo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('empresas_producto_rechazadas') && Schema::hasColumn('empresas_producto_rechazadas', 'motivo_rechazo')) {
            Schema::table('empresas_producto_rechazadas', function (Blueprint $table) {
                $table->dropColumn('motivo_rechazo');
            });
        }

        if (Schema::hasTable('productos_rechazados') && Schema::hasColumn('productos_rechazados', 'motivo_rechazo')) {
            Schema::table('productos_rechazados', function (Blueprint $table) {
                $table->dropColumn('motivo_rechazo');
            });
        }
    }
};
