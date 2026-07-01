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
        if (Schema::hasTable('empresas_producto_aprobadas') && Schema::hasColumn('empresas_producto_aprobadas', 'estado')) {
            DB::statement('ALTER TABLE `empresas_producto_aprobadas` MODIFY COLUMN `estado` VARCHAR(50) NOT NULL');
        }

        if (Schema::hasTable('productos_publicos') && Schema::hasColumn('productos_publicos', 'estado')) {
            DB::statement('ALTER TABLE `productos_publicos` MODIFY COLUMN `estado` VARCHAR(50) NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No reverse migration provided because the original enum definition is unknown.
    }
};
