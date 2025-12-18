@extends('layout.admin.plantilla')

@section('titulo', 'Calificar - ' . $asignatura->nombre)

@section('contenido')
<div class="w-full animate-fade-in">

    @if(session('success') || session('error'))
    <div id="resultModal" class="fixed inset-0 z-50 flex items-center justify-center">
        <!-- Overlay -->
        <div id="resultOverlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal box -->
            <div
                role="dialog"
                aria-modal="true"
                aria-labelledby="resultTitle"
                aria-describedby="resultMessage"
                class="relative z-10 w-[92%] max-w-lg mx-4 bg-white rounded-2xl shadow-2xl transform transition-all duration-300 scale-95 opacity-0"
                id="resultBox"
                tabindex="-1"
            >
                <div class="px-8 pt-8 pb-6">
                <!-- Icon -->
                <div class="flex justify-center -mt-8 mb-4">
                    @if(session('error'))
                    <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center">
                        <!-- X SVG -->
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="11" stroke="#F87171" stroke-width="0"></circle>
                        <path d="M15 9L9 15M9 9l6 6" stroke="#EF4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    @else
                    <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center">
                        <!-- Check SVG -->
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M20 6L9 17l-5-5" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    @endif
                </div>

                <!-- Title -->
                <h3 id="resultTitle" class="text-center font-extrabold text-2xl leading-tight
                    @if(session('error')) text-red-600 @else text-green-600 @endif">
                    {{ session('error') ? 'Error' : 'Éxito' }}
                </h3>

                <!-- Message -->
                <p id="resultMessage" class="mt-3 text-center text-gray-700 text-base leading-relaxed">
                    {{ session('error') ?? session('success') }}
                </p>

                <!-- Actions -->
                <div class="mt-6">
                    <button
                    id="resultOk"
                    type="button"
                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold
                            bg-blue-600 hover:bg-blue-700 text-white transition-shadow focus:outline-none focus:ring-4 focus:ring-blue-300"
                    >
                    Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Calificar Asignatura</h1>
                <p class="text-sm text-blue-600 font-medium flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    {{ $asignatura->nombre }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="badge badge-lg bg-blue-100 text-blue-800 border-blue-200 rounded-3xl p-3 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Período {{ $periodoActual->nombre }}
                <br>
                <span class="text-xs">({{ \Carbon\Carbon::parse($periodoActual->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($periodoActual->fecha_fin)->format('d/m/Y') }})</span>
            </span>
            
            <span class="badge badge-lg bg-green-100 text-green-800 border-green-200 rounded-3xl p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ $matriculas->count() }} estudiantes
            </span>
        </div>
    </div>

    <!-- Información de Competencias -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Competencias a Evaluar:
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($competencias as $competencia)
            <div class="bg-white p-3 rounded-lg border border-blue-100">
                <p class="text-sm text-gray-700">
                    <span class="font-semibold text-blue-600">C{{ $loop->index + 1 }}:</span> 
                    {{ $competencia->descripcion }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tabla de Calificaciones -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
        <form id="formCalificaciones" method="POST" action="{{ route('reporte_notas.guardar-masivas') }}">
            @csrf

            <input type="hidden" name="id_asignatura" value="{{ $asignatura->codigo_asignatura }}">
            <input type="hidden" name="id_periodo" value="{{ $periodoActual->id_periodo }}">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- Header Dinámico: Competencias por Período -->
                    <thead class="bg-gradient-to-r from-blue-500 to-blue-600">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider sticky left-0 bg-gradient-to-r from-blue-500 to-blue-600 z-10 min-w-max">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Estudiante
                                </div>
                            </th>
                            @foreach($competencias as $index => $competencia)
                            <th scope="col" class="px-4 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider border-l border-blue-400">
                                <div class="font-bold text-sm">C{{ $index + 1 }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>

                    <!-- Body: Estudiantes -->
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($matriculas as $matricula)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <!-- Nombre del Estudiante -->
                            <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white hover:bg-gray-50 z-5 min-w-max">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}
                                    </div>
                                </div>
                            </td>

                            <!-- Inputs de Calificaciones por Competencia -->
                            @foreach($competencias as $index => $competencia)
                            <td class="px-4 py-4 text-center border-l border-gray-200">
                                @php
                                    $detallesEstudiante = $detalles->get($matricula->codigo_matricula, collect());
                                    $detalleCompetencia = $detallesEstudiante->firstWhere('id_competencias', $competencia->id_competencias);
                                    $reporteActual = $detalleCompetencia?->reportesNotas->first();
                                    $modalId = 'modal_' . $matricula->codigo_matricula . '_' . $competencia->id_competencias;
                                @endphp

                                @if($notasRegistradas && $reporteActual)
                                    <!-- Modo edición: si las notas ya están registradas -->
                                    <div class="flex gap-2 items-center">
                                        <select name="calificaciones_editar_valores[{{ $reporteActual->id_reporte_notas }}]" 
                                                class="flex-1 px-2 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent font-semibold">
                                            <option value="">-</option>
                                            <option value="AD" {{ $reporteActual->calificacion === 'AD' ? 'selected' : '' }}>AD</option>
                                            <option value="A" {{ $reporteActual->calificacion === 'A' ? 'selected' : '' }}>A</option>
                                            <option value="B" {{ $reporteActual->calificacion === 'B' ? 'selected' : '' }}>B</option>
                                            <option value="C" {{ $reporteActual->calificacion === 'C' ? 'selected' : '' }}>C</option>
                                        </select>
                                        <button type="button" 
                                            onclick="openObservationModal('{{ $modalId }}')"
                                                class="p-2 rounded-md text-blue-600 hover:bg-blue-50 transition-colors"
                                                title="Agregar observación">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <!-- Modo registro: si aún no hay notas -->
                                    @if($detalleCompetencia)
                                    <div class="flex gap-2 items-center">
                                        <select name="calificaciones[{{ $detalleCompetencia->id_detalle_asignatura }}]" 
                                                class="flex-1 px-2 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent font-semibold">
                                            <option value="">-</option>
                                            <option value="AD">AD</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                        </select>
                                        <button type="button" 
                                            onclick="openModal('{{ $modalId }}')"
                                                class="p-2 rounded-md text-blue-600 hover:bg-blue-50 transition-colors"
                                                title="Agregar observación">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6" />
                                            </svg>
                                        </button>
                                    </div>
                                    @else
                                    <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                @endif

                                <!-- Modal personalizado de observación -->
                                @if($detalleCompetencia)
                                <div id="{{ $modalId }}" class="fixed inset-0 z-50 flex items-center justify-center hidden">
                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>

                                    <!-- Modal box -->
                                    <div class="relative z-10 w-[92%] max-w-lg mx-4 bg-white rounded-2xl shadow-2xl transform transition-all duration-300">
                                        <div class="px-6 pt-6 pb-4">
                                            <div class="flex justify-between items-start mb-4">
                                                <h3 class="font-bold text-lg text-gray-800 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Observación - C{{ $index + 1 }}
                                                </h3>
                                                <button type="button" onclick="closeObservationModal('{{ $modalId }}')" 
                                                        class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="bg-blue-50 p-3 rounded-lg mb-4">
                                                <p class="text-sm text-blue-800 font-semibold mb-1">Estudiante:</p>
                                                <p class="text-sm text-blue-900">
                                                    {{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}
                                                </p>
                                            </div>

                                            <div class="form-control w-full mb-6">
                                                <label class="label">
                                                    <span class="label-text font-semibold text-gray-700">Descripción del desempeño:</span>
                                                </label>
                                                @if($reporteActual)
                                                    <textarea 
                                                        id="textarea_{{ $modalId }}"
                                                        data-name="observaciones[{{ $reporteActual->id_reporte_notas }}]"
                                                        class="w-full h-32 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        placeholder="Describe cómo va el estudiante en esta competencia..."
                                                        >{{ $reporteActual->observacion ?? '' }}</textarea>
                                                @else
                                                    <textarea 
                                                        id="textarea_{{ $modalId }}"
                                                        data-name="observaciones[{{ $detalleCompetencia->id_detalle_asignatura }}]"
                                                        class="w-full h-32 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        placeholder="Describe cómo va el estudiante en esta competencia..."
                                                        ></textarea>
                                                @endif
                                            </div>

                                            <div class="flex justify-end gap-3">
                                                <button type="button" 
                                                        onclick="closeObservationModal('{{ $modalId }}')"
                                                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                                                    Cancelar
                                                </button>
                                                <button type="button" 
                                                        onclick="saveObservation('{{ $modalId }}')"
                                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition-colors flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $competencias->count() + 1 }}" class="px-6 py-8 text-center">
                                <p class="text-gray-500">No hay estudiantes matriculados en esta asignatura</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Botones de acción -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                <a href="{{ route('docentes.asignaturas') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md shadow-sm text-sm font-medium hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancelar
                </a>

                @if(!$notasRegistradas)
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Registrar Calificaciones
                    </button>
                @else
                        <button type="button" 
                            onclick="submitEdits()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Calificaciones
                    </button>
                @endif
            </div>
        </form>

        <!-- Formulario oculto para editar -->
        @if($notasRegistradas)
        <form id="formEditar" method="POST" action="{{ route('reporte_notas.actualizar-masivas') }}" style="display: none;">
            @csrf
            <input type="hidden" name="id_periodo" value="{{ $periodoActual->id_periodo }}">
            @foreach($matriculas as $matricula)
                @php
                    $detallesEstudiante = $detalles->get($matricula->codigo_matricula, collect());
                @endphp
                @foreach($competencias as $competencia)
                    @php
                        $detalleCompetencia = $detallesEstudiante->firstWhere('id_competencias', $competencia->id_competencias);
                        $reporteActual = $detalleCompetencia?->reportesNotas->first();
                    @endphp
                    @if($reporteActual)
                        <input type="hidden" name="calificaciones[{{ $reporteActual->id_reporte_notas }}]" value="{{ $reporteActual->calificacion }}">
                        @if($reporteActual->observacion)
                        <input type="hidden" name="observaciones[{{ $reporteActual->id_reporte_notas }}]" value="{{ $reporteActual->observacion }}">
                        @endif
                    @endif
                @endforeach
            @endforeach
        </form>
        @endif
    </div>
</div>

<script>
    const __resultRedirect = "{{ session('redirect_to') ?? '' }}";

    function openResultModal() {
        const modal = document.getElementById('resultModal');
        if (!modal) return;
        modal.style.display = 'flex';
        const box = modal.querySelector('#resultBox');
        if (box) {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }
        document.documentElement.classList.add('overflow-hidden');
        document.getElementById('resultOk')?.focus();
    }

    function closeResultModal() {
        const modal = document.getElementById('resultModal');
        if (!modal) return;
        const box = modal.querySelector('#resultBox');
        if (box) {
            box.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.style.display = 'none';
                document.documentElement.classList.remove('overflow-hidden');
                if (__resultRedirect.trim()) {
                    window.location.href = __resultRedirect;
                }
            }, 220);
        }
    }

    function openObservationModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.documentElement.classList.add('overflow-hidden');

        // Enfocar textarea
        setTimeout(() => {
            const textarea = modal.querySelector('textarea');
            textarea?.focus();
        }, 100);
    }

    function closeObservationModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.documentElement.classList.remove('overflow-hidden');
    }

    function saveObservation(id) {
        const modal = document.getElementById(id);
        const textarea = modal?.querySelector('textarea');
        const mainForm = document.getElementById('formCalificaciones');

        if (!textarea || !mainForm) return;

        const fieldName = textarea.getAttribute('data-name');
        const value = textarea.value.trim();

        // Crear o actualizar input hidden en el formulario principal
        let input = mainForm.querySelector(`input[name='${CSS.escape(fieldName)}']`);
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = fieldName;
            mainForm.appendChild(input);
        }
        input.value = value;

        // Cerrar modal
        closeObservationModal(id);
    }

    // Cerrar modales al hacer clic en el overlay
    document.addEventListener('click', function(e) {
        // Modal de confirmación
        const resultOverlay = document.getElementById('resultOverlay');
        if (resultOverlay && e.target === resultOverlay) {
            closeResultModal();
        }

        // Modales de observación
        const observationModals = document.querySelectorAll('.fixed.inset-0.z-50.flex:not(.hidden)');
        observationModals.forEach(modal => {
            const overlay = modal.querySelector('.absolute.inset-0');
            if (overlay && e.target === overlay) {
                const id = modal.id;
                if (id && id.startsWith('modal_')) {
                    closeObservationModal(id);
                }
            }
        });
    });

    // Cerrar con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Cerrar modal de confirmación
            if (document.getElementById('resultModal')?.style.display === 'flex') {
                closeResultModal();
                return;
            }

            // Cerrar último modal de observación abierto
            const openModal = document.querySelector('.fixed.inset-0.z-50.flex:not(.hidden)[id^="modal_"]');
            if (openModal) {
                closeObservationModal(openModal.id);
            }
        }
    });

    // Inicialización
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success') || session('error'))
            openResultModal();
            document.getElementById('resultOk')?.addEventListener('click', closeResultModal);
        @endif
    });

    // === VALIDACIÓN Y EDICIÓN ===
    document.getElementById('formCalificaciones')?.addEventListener('submit', function(e) {
        const selects = this.querySelectorAll('select[name^="calificaciones"]');
        const hasGrade = Array.from(selects).some(sel => sel.value.trim() !== '');
        if (!hasGrade) {
            e.preventDefault();
            alert('Debe ingresar al menos una calificación.');
        }
    });

    document.querySelectorAll('select[name^="calificaciones_editar_valores"]').forEach(select => {
        select.addEventListener('change', () => {
            select.classList.add('bg-yellow-50', 'border-yellow-400');
        });
    });

    function submitEdits() {
        const formEditar = document.getElementById('formEditar');
        if (!formEditar) return;

        document.querySelectorAll('select[name^="calificaciones_editar_valores"]').forEach(select => {
            const match = select.name.match(/calificaciones_editar_valores\[(\d+)\]/);
            if (!match) return;
            const id = match[1];
            const value = select.value || '';

            let input = formEditar.querySelector(`input[name="calificaciones[${CSS.escape(id)}]"]`);
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = `calificaciones[${id}]`;
                formEditar.appendChild(input);
            }
            input.value = value;
        });

        document.querySelectorAll('input[name^="observaciones["]').forEach(orig => {
            let existing = formEditar.querySelector(`input[name="${CSS.escape(orig.name)}"]`);
            if (!existing) {
                const clone = orig.cloneNode(true);
                formEditar.appendChild(clone);
            } else {
                existing.value = orig.value;
            }
        });

        formEditar.submit();
    }
</script>
@endsection