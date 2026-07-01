<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('publicaciones_publicas', function (Blueprint $table) {
            if (!Schema::hasColumn('publicaciones_publicas', 'requisitos')) {
                $table->text('requisitos')->nullable();
            }
            if (!Schema::hasColumn('publicaciones_publicas', 'imagen_trabajo')) {
                $table->string('imagen_trabajo')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('publicaciones_publicas', function (Blueprint $table) {
            if (Schema::hasColumn('publicaciones_publicas', 'requisitos')) {
                $table->dropColumn('requisitos');
            }
            if (Schema::hasColumn('publicaciones_publicas', 'imagen_trabajo')) {
                $table->dropColumn('imagen_trabajo');
            }
        });
    }
};
