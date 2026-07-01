<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('empresas_bolsadetrabajo_rechazadas')) {
            Schema::create('empresas_bolsadetrabajo_rechazadas', function (Blueprint $table) {
                $table->id('id_rechazado');
                $table->unsignedBigInteger('id_empresa_original')->nullable();
                $table->unsignedBigInteger('id_publicacion_original')->nullable();
                $table->unsignedBigInteger('id_usuario_rechazador')->nullable();
                $table->string('nombre_empresa')->nullable();
                $table->string('ruc')->nullable();
                $table->string('correo_electronico')->nullable();
                $table->string('telefono')->nullable();
                $table->string('responsable_representante')->nullable();
                $table->string('direccion')->nullable();
                $table->string('documento_validacion')->nullable();
                $table->string('titulo_puesto')->nullable();
                $table->text('descripcion_puesto')->nullable();
                $table->text('requisitos')->nullable();
                $table->string('imagen_trabajo')->nullable();
                $table->string('modalidad')->nullable();
                $table->string('categoria')->nullable();
                $table->decimal('salario', 12, 2)->nullable();
                $table->string('ubicacion')->nullable();
                $table->date('fecha_inicio_convocatoria')->nullable();
                $table->date('fecha_limite_postulacion')->nullable();
                $table->integer('veces_restaurado')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('empresas_bolsadetrabajo_rechazadas');
    }
};
