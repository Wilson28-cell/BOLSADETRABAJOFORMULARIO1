<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE publicaciones_publicas MODIFY COLUMN ubicacion TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE publicaciones_publicas MODIFY COLUMN ubicacion VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');
    }
};
