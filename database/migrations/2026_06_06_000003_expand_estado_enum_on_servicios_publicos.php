<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `servicios_publicos` MODIFY COLUMN `estado` ENUM('Publicado','Desactivado') NOT NULL DEFAULT 'Publicado';");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `servicios_publicos` MODIFY COLUMN `estado` ENUM('Publicado') NOT NULL DEFAULT 'Publicado';");
    }
};
