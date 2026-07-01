<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `registro_empresa_servicio` MODIFY COLUMN `estado` ENUM('Pendiente','Publicado','Rechazado') NULL DEFAULT 'Pendiente';");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `registro_empresa_servicio` MODIFY COLUMN `estado` ENUM('Pendiente') NULL DEFAULT 'Pendiente';");
    }
};
