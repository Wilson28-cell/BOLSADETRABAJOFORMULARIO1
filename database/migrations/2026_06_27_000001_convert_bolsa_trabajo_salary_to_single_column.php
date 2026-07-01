<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'publicaciones_trabajo',
            'empresas_bolsadetrabajo_aprobadas',
            'publicaciones_publicas',
            'empresas_bolsadetrabajo_rechazadas',
            'registro_publicidad_bolsa_trabajo',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            if (!Schema::hasColumn($table, 'salario')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->decimal('salario', 12, 2)->nullable()->after('categoria');
                });
            }

            if (Schema::hasColumn($table, 'salario_minimo') || Schema::hasColumn($table, 'salario_maximo')) {
                DB::table($table)->update([
                    'salario' => DB::raw('COALESCE(salario_minimo, salario_maximo)'),
                ]);
            }

            if (Schema::hasColumn($table, 'salario_minimo')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->dropColumn('salario_minimo');
                });
            }

            if (Schema::hasColumn($table, 'salario_maximo')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->dropColumn('salario_maximo');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'publicaciones_trabajo',
            'empresas_bolsadetrabajo_aprobadas',
            'publicaciones_publicas',
            'empresas_bolsadetrabajo_rechazadas',
            'registro_publicidad_bolsa_trabajo',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            if (!Schema::hasColumn($table, 'salario_minimo')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->decimal('salario_minimo', 12, 2)->nullable();
                });
            }

            if (!Schema::hasColumn($table, 'salario_maximo')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->decimal('salario_maximo', 12, 2)->nullable();
                });
            }

            if (Schema::hasColumn($table, 'salario')) {
                DB::table($table)->update([
                    'salario_minimo' => DB::raw('salario'),
                    'salario_maximo' => DB::raw('salario'),
                ]);
            }
        }
    }
};
