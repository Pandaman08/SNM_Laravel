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
            $this->command->error('Error: Los niveles educativos deben existir (Primaria, Secundaria).');
            return;
        }

        // Obtener los grados usando la columna correcta: nivel_educativo_id
        $gradosPrimaria   = Grado::where('nivel_educativo_id', $primaria->id_nivel_educativo)->get();
        $gradosSecundaria = Grado::where('nivel_educativo_id', $secundaria->id_nivel_educativo)->get();

        // Definir los nombres de secciones (solo A y B)
        $secciones = ['A', 'B'];
        $contadorSecciones = 0;

        // Configuración por defecto para las secciones
        $vacantesDefault = 30;
        $estadoVacantes = true;

        // Crear secciones para PRIMARIA (2 secciones por cada grado: A, B)
        foreach ($gradosPrimaria as $grado) {
            foreach ($secciones as $seccion) {
                DB::table('secciones')->insert([
                    'id_grado'          => $grado->id_grado,
                    'seccion'           => $seccion,
                    'vacantes_seccion'  => $vacantesDefault,
                    'estado_vacantes'   => $estadoVacantes,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
                $contadorSecciones++;
            }
        }

        // Crear secciones para SECUNDARIA (2 secciones por cada grado: A, B)
        foreach ($gradosSecundaria as $grado) {
            foreach ($secciones as $seccion) {
                DB::table('secciones')->insert([
                    'id_grado'          => $grado->id_grado,
                    'seccion'           => $seccion,
                    'vacantes_seccion'  => $vacantesDefault,
                    'estado_vacantes'   => $estadoVacantes,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
                $contadorSecciones++;
            }
        }

        // Mensajes de resumen
        $this->command->info('✅ Secciones creadas para el sistema peruano:');

        $this->command->info('📖 PRIMARIA:');
        foreach ($gradosPrimaria as $grado) {
            $this->command->info("   • Grado {$grado->grado}° → Secciones: A, B (Vacantes: {$vacantesDefault})");
        }

        $this->command->info('🎓 SECUNDARIA:');
        foreach ($gradosSecundaria as $grado) {
            $this->command->info("   • Grado {$grado->grado}° → Secciones: A, B (Vacantes: {$vacantesDefault})");
        }

        $this->command->line('');
        $this->command->info("📊 RESUMEN:");
        $this->command->info("   📖 Primaria: " . ($gradosPrimaria->count() * 2) . " secciones");
        $this->command->info("   🎓 Secundaria: " . ($gradosSecundaria->count() * 2) . " secciones");
        $this->command->info("   🎯 TOTAL: {$contadorSecciones} secciones");

        if ($contadorSecciones === 0) {
            $this->command->warn("⚠️  No se crearon secciones. Verifica que existen registros en 'grados' con nivel_educativo_id correcto.");
        }
    }
}