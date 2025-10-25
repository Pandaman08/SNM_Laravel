<?php

namespace Database\Seeders;

use App\Models\NivelEducativo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradosSeeder extends Seeder
{
    public function run(): void
    {
        $primaria   = NivelEducativo::where('nombre', 'Primaria')->first();
        $secundaria = NivelEducativo::where('nombre', 'Secundaria')->first();

        if (! $primaria || ! $secundaria) {
            $this->command->error('Error: Los niveles educativos (INI, PRI, SEC) deben crearse primero.');
            return;
        }

        $gradosPrimaria = [
            ['nivel_educativo_id' => $primaria->id_nivel_educativo, 'grado' => 1],
            ['nivel_educativo_id' => $primaria->id_nivel_educativo, 'grado' => 2],
            ['nivel_educativo_id' => $primaria->id_nivel_educativo, 'grado' => 3],
            ['nivel_educativo_id' => $primaria->id_nivel_educativo, 'grado' => 4],
            ['nivel_educativo_id' => $primaria->id_nivel_educativo, 'grado' => 5],
            ['nivel_educativo_id' => $primaria->id_nivel_educativo, 'grado' => 6],
        ];

        $gradosSecundaria = [
            ['nivel_educativo_id' => $secundaria->id_nivel_educativo, 'grado' => 1],
            ['nivel_educativo_id' => $secundaria->id_nivel_educativo, 'grado' => 2],
            ['nivel_educativo_id' => $secundaria->id_nivel_educativo, 'grado' => 3],
            ['nivel_educativo_id' => $secundaria->id_nivel_educativo, 'grado' => 4],
            ['nivel_educativo_id' => $secundaria->id_nivel_educativo, 'grado' => 5],
        ];

        // 3) Insertar todos los grados, evitando duplicados
        $todosLosGrados = array_merge($gradosPrimaria, $gradosSecundaria);

        foreach ($todosLosGrados as $gradoData) {
            $gradoData['created_at'] = now();
            $gradoData['updated_at'] = now();

            // Verificar si ya existe para evitar duplicar
            $existe = DB::table('grados')
                ->where('nivel_educativo_id', $gradoData['nivel_educativo_id'])
                ->where('grado', $gradoData['grado'])
                ->exists();

            if (! $existe) {
                DB::table('grados')->insert($gradoData);
            } else {
                $this->command->info("⚠️  Grado {$gradoData['grado']} (nivel {$gradoData['nivel_educativo_id']}) ya existe. Se omite.");
            }
        }

        // 4) Mensajes de resumen
        $this->command->info('✅ Grados del sistema educativo peruano creados o verificados:');
        $this->command->line('');

        $this->command->info('📖 PRIMARIA (1°-6° grado):');
        foreach ($gradosPrimaria as $grado) {
            $this->command->info("   • {$grado['grado']}° grado (Nivel ID: {$grado['nivel_educativo_id']})");
        }

        $this->command->info('🎓 SECUNDARIA (1°-5° año):');
        foreach ($gradosSecundaria as $grado) {
            $this->command->info("   • {$grado['grado']}° año (Nivel ID: {$grado['nivel_educativo_id']})");
        }

        $this->command->line('');
        $this->command->info('📊 TOTAL (procesados): ' . count($todosLosGrados) . ' registros.');
        $this->command->info('🎯 Estructura lista para sistema de matrículas');
    }
}
