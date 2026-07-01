<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_publicidad_bolsa_trabajo', function (Blueprint $table) {
            $table->id('id_publicidad');
            $table->integer('id_aprobado')->nullable();
            $table->integer('id_empresa_original')->nullable();
            $table->integer('id_publicacion_original')->nullable();
            $table->integer('id_usuario_aprobador')->nullable();
            $table->string('nombre_empresa')->nullable();
            $table->string('ruc')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('telefono')->nullable();
            $table->string('responsable_representante')->nullable();
            $table->text('direccion')->nullable();
            $table->string('documento_validacion')->nullable();
            $table->string('titulo_puesto')->nullable();
            $table->text('descripcion_puesto')->nullable();
            $table->text('requisitos')->nullable();
            $table->string('imagen_trabajo')->nullable();
            $table->string('modalidad')->nullable();
            $table->string('categoria')->nullable();
            $table->decimal('salario', 12, 2)->nullable();
            $table->text('ubicacion')->nullable();
            $table->date('fecha_inicio_convocatoria')->nullable();
            $table->date('fecha_limite_postulacion')->nullable();
            $table->string('estado')->nullable();
            $table->string('documento_aprobacion_pdf')->nullable();
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->timestamp('fecha_desactivacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registro_publicidad_bolsa_trabajo');
    }
};
