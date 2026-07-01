<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servicios_aprobados', function (Blueprint $table) {
            $table->id('id_aprobado');
            $table->unsignedBigInteger('id_empresa_servicio');
            $table->string('nombre_empresa');
            $table->string('ruc', 11)->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('telefono')->nullable();
            $table->string('responsable_representante')->nullable();
            $table->string('direccion')->nullable();
            $table->string('documento_validacion')->nullable();
            $table->string('documento_validacion_subgerencia')->nullable();
            $table->string('estado')->default('Publicado');
            $table->date('fecha_registro')->nullable();
            $table->unsignedBigInteger('id_servicio_original')->nullable();
            $table->string('nombre_servicio')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('categoria')->nullable();
            $table->string('ubicacion_ciudad')->nullable();
            $table->string('telefono_contacto')->nullable();
            $table->string('redes_sociales')->nullable();
            $table->string('correo_contacto')->nullable();
            $table->string('direccion_atencion')->nullable();
            $table->string('imagen_servicio')->nullable();
            $table->string('horario_atencion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
        });

        Schema::create('servicios_rechazados', function (Blueprint $table) {
            $table->id('id_rechazado');
            $table->unsignedBigInteger('id_empresa_servicio');
            $table->string('nombre_empresa');
            $table->string('ruc', 11)->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('telefono')->nullable();
            $table->string('responsable_representante')->nullable();
            $table->string('direccion')->nullable();
            $table->string('documento_validacion')->nullable();
            $table->string('estado')->default('Rechazado');
            $table->date('fecha_registro')->nullable();
            $table->unsignedBigInteger('id_servicio_original')->nullable();
            $table->string('nombre_servicio')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('categoria')->nullable();
            $table->string('ubicacion_ciudad')->nullable();
            $table->string('telefono_contacto')->nullable();
            $table->string('redes_sociales')->nullable();
            $table->string('correo_contacto')->nullable();
            $table->string('direccion_atencion')->nullable();
            $table->string('imagen_servicio')->nullable();
            $table->string('horario_atencion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicios_aprobados');
        Schema::dropIfExists('servicios_rechazados');
    }
};
