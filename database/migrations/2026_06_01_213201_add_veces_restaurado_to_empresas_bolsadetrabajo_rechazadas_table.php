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
        Schema::table('empresas_bolsadetrabajo_rechazadas', function (Blueprint $table) {
            if (!Schema::hasColumn('empresas_bolsadetrabajo_rechazadas', 'veces_restaurado')) {
                $table->integer('veces_restaurado')->default(0)->after('fecha_rechazo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas_bolsadetrabajo_rechazadas', function (Blueprint $table) {
            $table->dropColumn('veces_restaurado');
        });
    }
};
