<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('registro_bolsadetrabajo_empresa', function (Blueprint $table) {
            if (!Schema::hasColumn('registro_bolsadetrabajo_empresa', 'veces_restaurado')) {
                $table->integer('veces_restaurado')->default(0);
            }
            if (!Schema::hasColumn('registro_bolsadetrabajo_empresa', 'estado')) {
                $table->string('estado')->default('PENDIENTE');
            }
        });
    }

    public function down()
    {
        Schema::table('registro_bolsadetrabajo_empresa', function (Blueprint $table) {
            if (Schema::hasColumn('registro_bolsadetrabajo_empresa', 'veces_restaurado')) {
                $table->dropColumn('veces_restaurado');
            }
            if (Schema::hasColumn('registro_bolsadetrabajo_empresa', 'estado')) {
                $table->dropColumn('estado');
            }
        });
    }
};
