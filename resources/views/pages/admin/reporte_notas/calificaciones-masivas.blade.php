@extends('layout.admin.plantilla')

@section('titulo', 'Calificar - ' . $asignatura->nombre)

@section('contenido')
<div class="w-full animate-fade-in space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Calificación por Competencia</h1>
                <p class="text-sm text-blue-600 font-medium flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    {{ $asignatura->nombre }}
                </p>
            </div>
        </div>
    </div>

    <!-- Controls Section -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6 items-center justify-between">
        
        <!-- Competencia Selector -->
        <div class="w-full md:w-1/2">
            <label for="competencia_id" class="block text-sm font-medium text-gray-700 mb-2">Seleccione Competencia a Evaluar</label>
            <select id="competencia_id" name="competencia_id" onchange="cargarDatos()" class="w-full pl-4 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-xl shadow-sm transition-all cursor-pointer bg-gray-50 hover:bg-white">
                <option value="">-- Seleccionar Competencia --</option>
                @foreach($competencias as $comp)
                    <option value="{{ $comp->id_competencias }}">{{ $comp->descripcion }}</option>
                @endforeach
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2 w-full md:w-auto flex-wrap justify-end">
            <button type="button" onclick="regresar()" class="px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all flex items-center justify-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Regresar
            </button>
            <button type="button" onclick="cancelarEdicion()" class="px-3 py-2 border border-transparent text-xs font-medium rounded-lg text-white bg-red-500 shadow-lg hover:bg-red-700 hover:shadow-red-500/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all" id="btnCancelar" style="display: none;">
                ✕ Cancelar
            </button>
            <button type="button" onclick="guardarNotas()" class="px-3 py-2 border border-transparent text-xs font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-lg hover:shadow-blue-500/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all flex items-center justify-center gap-1" id="btnGuardar" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Guardar Notas
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingIndicator" class="hidden py-12 flex justify-center items-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Tabla Dinámica -->
    <div id="tablaContainer" class="hidden bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up">
        <form id="formNotas">
            <input type="hidden" name="id_asignatura" value="{{ $asignatura->codigo_asignatura }}">
            <input type="hidden" id="hidden_competencia_id" name="id_competencia">
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-500 to-blue-600">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider sticky left-0 bg-gradient-to-r from-blue-500 to-blue-600 z-10 min-w-max">
                                Estudiante
                            </th>
                            @foreach($periodos as $periodo)
                                <th scope="col" class="px-4 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider border-l border-blue-400">
                                    <div class="flex flex-col items-center">
                                        <span>{{ $periodo->nombre }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="tablaBody" class="bg-white divide-y divide-gray-200">
                        <!-- Rows injected by JS -->
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="py-12 flex flex-col items-center justify-center text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="text-lg font-medium">Seleccione una competencia para comenzar</p>
    </div>

</div>

<!-- Result Modal -->
<div id="resultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="relative z-10 w-[92%] max-w-sm mx-4 bg-white rounded-2xl shadow-2xl p-6 text-center">
        <div id="modalIcon" class="mb-4 flex justify-center"></div>
        <h3 id="modalTitle" class="text-xl font-bold mb-2"></h3>
        <p id="modalMessage" class="text-gray-600 mb-6"></p>
        <button onclick="closeModal()" class="w-full py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium">Aceptar</button>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeConfirmationModal()"></div>
    <div class="relative z-10 w-[92%] max-w-sm mx-4 bg-white rounded-2xl shadow-2xl p-6">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <h3 id="confirmTitle" class="text-xl font-bold mb-2 text-center text-gray-800"></h3>
        <p id="confirmMessage" class="text-gray-600 mb-6 text-center"></p>
        <div class="flex gap-3">
            <button onclick="closeConfirmationModal()" class="flex-1 py-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 transition-colors font-medium">Cancelar</button>
            <button id="confirmButton" onclick="executeConfirmation()" class="flex-1 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">Aceptar</button>
        </div>
    </div>
</div>

<script>
    const asignaturaId = {{ $asignatura->codigo_asignatura }};
    const periodos = @json($periodos); // [{id_periodo, nombre, estado}, ...]
    
    // Identificar periodos activos (Proceso)
    const periodosActivos = periodos.filter(p => p.estado === 'Proceso').map(p => p.id_periodo);

    function cargarDatos() {
        const compId = document.getElementById('competencia_id').value;
        const container = document.getElementById('tablaContainer');
        const empty = document.getElementById('emptyState');
        const loading = document.getElementById('loadingIndicator');
        const btnCancelar = document.getElementById('btnCancelar');
        const btnGuardar = document.getElementById('btnGuardar');

        if (!compId) {
            container.classList.add('hidden');
            btnCancelar.style.display = 'none';
            btnGuardar.style.display = 'none';
            empty.classList.remove('hidden');
            return;
        }

        empty.classList.add('hidden');
        container.classList.add('hidden');
        loading.classList.remove('hidden');
        btnCancelar.style.display = 'inline-block';
        btnGuardar.style.display = 'inline-flex';

        // Set hidden input
        document.getElementById('hidden_competencia_id').value = compId;

        fetch(`/api/notas-competencia/${asignaturaId}/${compId}`)
            .then(res => res.json())
            .then(data => {
                renderTable(data.estudiantes);
                loading.classList.add('hidden');
                container.classList.remove('hidden');
                btnCancelar.style.display = 'inline-block';
                btnGuardar.style.display = 'inline-flex';
            })
            .catch(err => {
                console.error(err);
                loading.classList.add('hidden');
                showModal('error', 'Error al cargar datos');
            });
    }

    function renderTable(estudiantes) {
        const tbody = document.getElementById('tablaBody');
        tbody.innerHTML = '';

        // Obtener fecha actual en formato YYYY-MM-DD para comparar
        const hoy = new Date();
        const fechaHoy = hoy.toISOString().split('T')[0]; // "2025-01-13"

        estudiantes.forEach(est => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 transition-colors';

            let html = `
                <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white hover:bg-gray-50 z-5 border-r border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold border border-blue-100">
                            ${est.estudiante_nombre.charAt(0)}
                        </div>
                        <div class="text-sm font-medium text-gray-900">${est.estudiante_nombre}</div>
                    </div>
                </td>
            `;

            // Columnas Periodos
            periodos.forEach(p => {
                const nota = est.notas[p.id_periodo] || '';
                
                // ✅ Verificar si el periodo está activo (fecha actual entre inicio y fin)
                const fechaInicio = new Date(p.fecha_inicio);
                const fechaFin = new Date(p.fecha_fin);
                const isActive = hoy >= fechaInicio && hoy <= fechaFin;
                
                // O si prefieres usar solo el estado
                // const isActive = p.estado === 'Proceso';
                
                const disabled = !isActive ? 'disabled' : '';
                const bgClass = !isActive ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-white focus:ring-2 focus:ring-blue-500';
                
                html += `
                    <td class="px-4 py-3 text-center border-l border-gray-100">
                        <select 
                            name="notas[${est.codigo_matricula}][${p.id_periodo}]" 
                            class="w-20 text-center text-sm font-semibold rounded-lg border-gray-300 shadow-sm ${bgClass}"
                            ${disabled}
                        >
                            <option value="">-</option>
                            <option value="AD" ${nota === 'AD' ? 'selected' : ''}>AD</option>
                            <option value="A" ${nota === 'A' ? 'selected' : ''}>A</option>
                            <option value="B" ${nota === 'B' ? 'selected' : ''}>B</option>
                            <option value="C" ${nota === 'C' ? 'selected' : ''}>C</option>
                        </select>
                    </td>
                `;
            });

            tr.innerHTML = html;
            tbody.appendChild(tr);
        });
    }

    function guardarNotas() {
        const form = document.getElementById('formNotas');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries()); // Esto no maneja arrays anidados bien

        // Convertir FormData a JSON estructurado para el backend
        // Como tenemos name="notas[matricula][periodo]", necesitamos parsearlo o enviarlo como form normal.
        // Fetch con FormData funciona bien si el backend acepta multipart/form-data o urlencoded.
        // Laravel maneja FormData automáticamente.

        fetch("{{ route('reporte_notas.guardar-competencia') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showModal('success', 'Notas guardadas correctamente');
                // Limpiar inputs y recargar datos
                limpiarFormulario();
                setTimeout(() => {
                    cargarDatos();
                }, 1500);
            } else {
                showModal('error', data.message || 'Error al guardar');
            }
        })
        .catch(err => {
            showModal('error', 'Error de conexión');
        });
    }

    function cancelarEdicion() {
        limpiarFormulario();
        const compSelect = document.getElementById('competencia_id');
        compSelect.value = '';
        cargarDatos();
    }

    function regresar() {
        showConfirmationModal('¿Regresar?', 'Los datos ingresados no se guardarán. ¿Desea continuar?', () => {
            window.location.href = '{{ route('docentes.asignaturas') }}';
        });
    }

    function limpiarFormulario() {
        const form = document.getElementById('formNotas');
        if (form) {
            const selects = form.querySelectorAll('select[name^="notas"]');
            selects.forEach(select => {
                select.value = '';
            });
        }
    }

    function showModal(type, message) {
        const modal = document.getElementById('resultModal');
        const title = document.getElementById('modalTitle');
        const msg = document.getElementById('modalMessage');
        const icon = document.getElementById('modalIcon');

        modal.classList.remove('hidden');
        title.innerText = type === 'success' ? '¡Éxito!' : 'Error';
        msg.innerText = message;
        
        if (type === 'success') {
            title.className = 'text-xl font-bold mb-2 text-green-600';
            icon.innerHTML = `<div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>`;
        } else {
            title.className = 'text-xl font-bold mb-2 text-red-600';
            icon.innerHTML = `<div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>`;
        }
    }
    
    function closeModal() {
        document.getElementById('resultModal').classList.add('hidden');
    }

    let confirmationCallback = null;

    function showConfirmationModal(title, message, callback) {
        const modal = document.getElementById('confirmationModal');
        document.getElementById('confirmTitle').innerText = title;
        document.getElementById('confirmMessage').innerText = message;
        confirmationCallback = callback;
        modal.classList.remove('hidden');
    }

    function closeConfirmationModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
        confirmationCallback = null;
    }

    function executeConfirmation() {
        if (confirmationCallback) {
            confirmationCallback();
        }
        closeConfirmationModal();
    }
</script>
@endsection
