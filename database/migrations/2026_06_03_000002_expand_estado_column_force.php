<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // Expandir columna estado en empresas_producto_aprobadas
        try {
            DB::statement('ALTER TABLE `empresas_producto_aprobadas` CHANGE COLUMN `estado` `estado` VARCHAR(50) DEFAULT NULL');
        } catch (\Exception $e) {
            // Si falla CHANGE, intentar MODIFY
            DB::statement('ALTER TABLE `empresas_producto_aprobadas` MODIFY COLUMN `estado` VARCHAR(50) DEFAULT NULL');
        }

        // Expandir columna estado en productos_publicos
        try {
            DB::statement('ALTER TABLE `productos_publicos` CHANGE COLUMN `estado` `estado` VARCHAR(50) DEFAULT NULL');
        } catch (\Exception $e) {
            // Si falla CHANGE, intentar MODIFY
            DB::statement('ALTER TABLE `productos_publicos` MODIFY COLUMN `estado` VARCHAR(50) DEFAULT NULL');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
