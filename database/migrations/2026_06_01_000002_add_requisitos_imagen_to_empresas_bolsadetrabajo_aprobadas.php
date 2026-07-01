<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('empresas_bolsadetrabajo_aprobadas', function (Blueprint $table) {
            if (!Schema::hasColumn('empresas_bolsadetrabajo_aprobadas', 'requisitos')) {
                $table->text('requisitos')->nullable();
            }
            if (!Schema::hasColumn('empresas_bolsadetrabajo_aprobadas', 'imagen_trabajo')) {
                $table->string('imagen_trabajo')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('empresas_bolsadetrabajo_aprobadas', function (Blueprint $table) {
            if (Schema::hasColumn('empresas_bolsadetrabajo_aprobadas', 'requisitos')) {
                $table->dropColumn('requisitos');
            }
            if (Schema::hasColumn('empresas_bolsadetrabajo_aprobadas', 'imagen_trabajo')) {
                $table->dropColumn('imagen_trabajo');
            }
        });
    }
};
