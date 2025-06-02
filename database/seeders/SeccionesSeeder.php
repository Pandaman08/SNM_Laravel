<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seccion;
use App\Models\Grado;
use App\Models\NivelEducativo;
use Illuminate\Support\Facades\DB;

class SeccionesSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Obtener los niveles educativos (usas id_nivel_educativo tal cual en la tabla)
        $inicial    = NivelEducativo::where('codigo', 'INI')->first();
        $primaria   = NivelEducativo::where('codigo', 'PRI')->first();
        $secundaria = NivelEducativo::where('codigo', 'SEC')->first();

        if (! $inicial || ! $primaria || ! $secundaria) {
            $this->command->error('Error: Los niveles educativos deben existir (INI, PRI, SEC).');
            return;
        }

        // 2) Obtener los grados usando la columna correcta: nivel_educativo_id
        $gradosIniciales  = Grado::where('nivel_educativo_id', $inicial->id_nivel_educativo)->get();
        $gradosPrimaria   = Grado::where('nivel_educativo_id', $primaria->id_nivel_educativo)->get();
        $gradosSecundaria = Grado::where('nivel_educativo_id', $secundaria->id_nivel_educativo)->get();

        // 3) Definir los nombres de secciones
        $secciones = ['A', 'B', 'C', 'D'];
        $contadorSecciones = 0;

        // 4) Crear secciones para INICIAL (2 por cada grado)
        foreach ($gradosIniciales as $grado) {
            // Ahora $grado->id_grado tiene valor (porque definimos primaryKey en el modelo)
            for ($i = 0; $i < 2; $i++) {
                DB::table('secciones')->insert([
                    'id_grado'   => $grado->id_grado,
                    'seccion'    => $secciones[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $contadorSecciones++;
            }
        }

        // 5) Crear secciones para PRIMARIA (3 por cada grado)
        foreach ($gradosPrimaria as $grado) {
            for ($i = 0; $i < 3; $i++) {
                DB::table('secciones')->insert([
                    'id_grado'   => $grado->id_grado,
                    'seccion'    => $secciones[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $contadorSecciones++;
            }
        }

        // 6) Crear secciones para SECUNDARIA (4 por cada grado)
        foreach ($gradosSecundaria as $grado) {
            for ($i = 0; $i < 4; $i++) {
                DB::table('secciones')->insert([
                    'id_grado'   => $grado->id_grado,
                    'seccion'    => $secciones[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $contadorSecciones++;
            }
        }

        // 7) Mensajes de resumen
        $this->command->info('✅ Secciones creadas para el sistema peruano:');
        $this->command->line('');

        $this->command->info('📚 INICIAL:');
        foreach ($gradosIniciales as $grado) {
            $this->command->info("   • Grado {$grado->grado} → Secciones: A, B");
        }

        $this->command->info('📖 PRIMARIA:');
        foreach ($gradosPrimaria as $grado) {
            $this->command->info("   • Grado {$grado->grado}° → Secciones: A, B, C");
        }

        $this->command->info('🎓 SECUNDARIA:');
        foreach ($gradosSecundaria as $grado) {
            $this->command->info("   • Grado {$grado->grado}° → Secciones: A, B, C, D");
        }

        $this->command->line('');
        $this->command->info("📊 RESUMEN:");
        $this->command->info("   📚 Inicial:  " . ($gradosIniciales->count()  * 2) . " secciones");
        $this->command->info("   📖 Primaria: " . ($gradosPrimaria->count()   * 3) . " secciones");
        $this->command->info("   🎓 Secundaria: " . ($gradosSecundaria->count() * 4) . " secciones");
        $this->command->info("   🎯 TOTAL: {$contadorSecciones} secciones");

        if ($contadorSecciones === 0) {
            $this->command->warn("⚠️  No se crearon secciones. Verifica que existen registros en 'grados' con nivel_educativo_id correcto.");
        }
    }
}
