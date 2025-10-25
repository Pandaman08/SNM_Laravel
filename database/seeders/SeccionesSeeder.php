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
        $primaria   = NivelEducativo::where('nombre', 'Primaria')->first();
        $secundaria = NivelEducativo::where('nombre', 'Secundaria')->first();

        if (! $primaria || ! $secundaria) {
            $this->command->error('Error: Los niveles educativos deben existir (INI, PRI, SEC).');
            return;
        }

        // 2) Obtener los grados usando la columna correcta: nivel_educativo_id
        $gradosPrimaria   = Grado::where('nivel_educativo_id', $primaria->id_nivel_educativo)->get();
        $gradosSecundaria = Grado::where('nivel_educativo_id', $secundaria->id_nivel_educativo)->get();

        // 3) Definir los nombres de secciones
        $secciones = ['A', 'B', 'C', 'D'];
        $contadorSecciones = 0;

        // Configuración por defecto para las secciones
        $vacantesDefault = 30; // Número de vacantes por defecto
        $estadoVacantes = true; // Estado de vacantes inicial (true = disponible)

        // 5) Crear secciones para PRIMARIA (3 por cada grado)
        foreach ($gradosPrimaria as $grado) {
            for ($i = 0; $i < 3; $i++) {
                DB::table('secciones')->insert([
                    'id_grado'          => $grado->id_grado,
                    'seccion'           => $secciones[$i],
                    'vacantes_seccion'  => $vacantesDefault,
                    'estado_vacantes'   => $estadoVacantes,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
                $contadorSecciones++;
            }
        }

        // 6) Crear secciones para SECUNDARIA (4 por cada grado)
        foreach ($gradosSecundaria as $grado) {
            for ($i = 0; $i < 4; $i++) {
                DB::table('secciones')->insert([
                    'id_grado'          => $grado->id_grado,
                    'seccion'           => $secciones[$i],
                    'vacantes_seccion'  => $vacantesDefault,
                    'estado_vacantes'   => $estadoVacantes,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
                $contadorSecciones++;
            }
        }

        // 7) Mensajes de resumen
        $this->command->info('✅ Secciones creadas para el sistema peruano:');

        $this->command->info('📖 PRIMARIA:');
        foreach ($gradosPrimaria as $grado) {
            $this->command->info("   • Grado {$grado->grado}° → Secciones: A, B, C (Vacantes: {$vacantesDefault})");
        }

        $this->command->info('🎓 SECUNDARIA:');
        foreach ($gradosSecundaria as $grado) {
            $this->command->info("   • Grado {$grado->grado}° → Secciones: A, B, C, D (Vacantes: {$vacantesDefault})");
        }

        $this->command->line('');
        $this->command->info("📊 RESUMEN:");
        $this->command->info("   📖 Primaria: " . ($gradosPrimaria->count() * 3) . " secciones");
        $this->command->info("   🎓 Secundaria: " . ($gradosSecundaria->count() * 4) . " secciones");
        $this->command->info("   🎯 TOTAL: {$contadorSecciones} secciones");

        if ($contadorSecciones === 0) {
            $this->command->warn("⚠️  No se crearon secciones. Verifica que existen registros en 'grados' con nivel_educativo_id correcto.");
        }
    }
}
