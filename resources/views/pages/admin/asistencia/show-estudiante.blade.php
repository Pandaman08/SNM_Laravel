@extends('layout.admin.plantilla')

@section('contenido')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header del estudiante -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($matricula->estudiante->persona->name, 0, 1) . substr($matricula->estudiante->persona->lastname, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}
                        </h1>
                        <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                Código: {{ $matricula->estudiante->codigo_estudiante }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $matricula->seccion->grado->getNombreCompletoAttribute() }} - {{ $matricula->seccion->seccion }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button onclick="exportarCalendario()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Exportar
                    </button>
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                        </svg>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>

        <!-- Resumen Estadístico -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            @php
                $totalesGenerales = ['Presente' => 0, 'Ausente' => 0, 'Justificado' => 0, 'Tarde' => 0];
                $totalDias = 0;
                foreach($statsPorPeriodo as $stat) {
                    foreach($stat['totales'] as $estado => $cantidad) {
                        if (isset($totalesGenerales[$estado])) {
                            $totalesGenerales[$estado] += $cantidad;
                        }
                    }
                }
                $totalDias = array_sum($totalesGenerales);
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Días Presente</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $totalesGenerales['Presente'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    {{ $totalDias > 0 ? round(($totalesGenerales['Presente'] / $totalDias) * 100, 1) : 0 }}%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Días Ausente</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $totalesGenerales['Ausente'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-red-600">
                                    {{ $totalDias > 0 ? round(($totalesGenerales['Ausente'] / $totalDias) * 100, 1) : 0 }}%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Días Justificado</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $totalesGenerales['Justificado'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-yellow-600">
                                    {{ $totalDias > 0 ? round(($totalesGenerales['Justificado'] / $totalDias) * 100, 1) : 0 }}%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Días Tarde</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $totalesGenerales['Tarde'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-orange-600">
                                    {{ $totalDias > 0 ? round(($totalesGenerales['Tarde'] / $totalDias) * 100, 1) : 0 }}%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Filtros</h2>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="mostrar-fines-semana" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                        <span class="ml-2 text-sm text-gray-700">Mostrar fines de semana</span>
                    </label>
                    <select id="vista-calendario" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="año">Vista Anual</option>
                        <option value="periodo">Por Períodos</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                    <select id="filtro-periodo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <option value="">Todos los períodos</option>
                        @foreach($statsPorPeriodo as $stat)
                            <option value="{{ $stat['periodo']['id_periodo'] }}">{{ $stat['periodo']['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="filtro-estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <option value="">Todos los estados</option>
                        <option value="Presente">Presente</option>
                        <option value="Ausente">Ausente</option>
                        <option value="Justificado">Justificado</option>
                        <option value="Tarde">Tarde</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Año</label>
                    <select id="filtro-año" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <!-- Se llenará dinámicamente -->
                    </select>
                </div>

                <div class="flex items-end">
                    <button id="limpiar-filtros" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                        Limpiar filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Leyenda -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap items-center justify-center gap-6 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                    <span class="text-gray-700">Presente</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                    <span class="text-gray-700">Ausente</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                    <span class="text-gray-700">Justificado</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
                    <span class="text-gray-700">Tarde</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-200 border border-gray-300 rounded mr-2"></div>
                    <span class="text-gray-700">Sin registro</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded mr-2"></div>
                    <span class="text-gray-700">Fin de semana</span>
                </div>
            </div>
        </div>

        <!-- Calendario Principal -->
        <div id="contenedor-calendario" class="space-y-8">
            <!-- Será generado dinámicamente por JavaScript -->
        </div>

        <!-- Panel de Información del Día Seleccionado -->
        <div id="panel-informacion" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-96 overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 id="titulo-informacion" class="text-lg font-semibold text-gray-900"></h3>
                        <button onclick="cerrarPanelInformacion()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <div id="contenido-informacion">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos de asistencias desde PHP - CORREGIDO para usar asistenciasPlanas
    const asistenciasData = @json($asistenciasPlanas);
    const periodosData = @json($statsPorPeriodo);
    
    console.log('Asistencias Data:', asistenciasData);
    console.log('Períodos Data:', periodosData);
    
    // Ya no necesitamos convertir porque los datos ya vienen en el formato correcto
    const asistenciasPorFecha = asistenciasData;

    // Elementos del DOM
    const contenedorCalendario = document.getElementById('contenedor-calendario');
    const filtros = {
        periodo: document.getElementById('filtro-periodo'),
        estado: document.getElementById('filtro-estado'),
        año: document.getElementById('filtro-año'),
        vistaTipo: document.getElementById('vista-calendario')
    };
    const mostrarFinesSemana = document.getElementById('mostrar-fines-semana');
    const limpiarFiltros = document.getElementById('limpiar-filtros');
    
    // Inicializar años disponibles
    function inicializarAños() {
        const años = new Set();
        periodosData.forEach(stat => {
            const fechaInicio = new Date(stat.periodo.fecha_inicio);
            const fechaFin = new Date(stat.periodo.fecha_fin);
            años.add(fechaInicio.getFullYear());
            años.add(fechaFin.getFullYear());
        });
        
        filtros.año.innerHTML = '<option value="">Todos los años</option>';
        Array.from(años).sort().forEach(año => {
            const option = document.createElement('option');
            option.value = año;
            option.textContent = año;
            if (año === new Date().getFullYear()) {
                option.selected = true;
            }
            filtros.año.appendChild(option);
        });
    }

    // Función para obtener el color según el estado
    function obtenerColorEstado(estado) {
        const colores = {
            'Presente': 'bg-green-500',
            'Ausente': 'bg-red-500',
            'Justificado': 'bg-yellow-500',
            'Tarde': 'bg-orange-500'
        };
        return colores[estado] || 'bg-gray-200';
    }

    // Función para crear un día del calendario
    function crearDiaCalendario(fecha, asistencia = null, esFindeSemana = false) {
        const div = document.createElement('div');
        const fechaStr = fecha.toISOString().split('T')[0];
        
        let clases = 'w-8 h-8 rounded text-xs flex items-center justify-center font-medium cursor-pointer transition-all duration-200 hover:scale-110 dia-calendario';
        
        if (esFindeSemana) {
            clases += ' bg-blue-50 border border-blue-200 text-blue-600';
            if (!mostrarFinesSemana.checked) {
                div.style.display = 'none';
            }
        } else if (asistencia) {
            clases += ` ${obtenerColorEstado(asistencia.estado)} text-white shadow-sm`;
        } else {
            clases += ' bg-gray-100 border border-gray-300 text-gray-500';
        }
        
        div.className = clases;
        div.textContent = fecha.getDate();
        div.dataset.fecha = fechaStr;
        div.dataset.finSemana = esFindeSemana;
        
        if (asistencia) {
            div.dataset.estado = asistencia.estado;
            div.title = `${fecha.toLocaleDateString('es-ES', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            })}\nEstado: ${asistencia.estado}`;
            
            // Event listener para mostrar información
            div.addEventListener('click', () => mostrarInformacionDia(fecha, asistencia));
        } else if (!esFindeSemana) {
            div.title = `${fecha.toLocaleDateString('es-ES', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            })}\nSin registro de asistencia`;
        } else {
            div.title = `${fecha.toLocaleDateString('es-ES', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            })}\nFin de semana`;
        }
        
        return div;
    }

    // Función para crear el calendario anual
    function crearCalendarioAnual(año) {
        const meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        
        const diasSemana = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
        
        contenedorCalendario.innerHTML = '';
        
        for (let mes = 0; mes < 12; mes++) {
            const divMes = document.createElement('div');
            divMes.className = 'bg-white rounded-xl shadow-sm border border-gray-200 p-6';
            
            // Título del mes
            const tituloMes = document.createElement('h3');
            tituloMes.className = 'text-lg font-semibold text-gray-900 mb-4 text-center';
            tituloMes.textContent = `${meses[mes]} ${año}`;
            divMes.appendChild(tituloMes);
            
            // Encabezados de días de la semana
            const encabezados = document.createElement('div');
            encabezados.className = 'grid grid-cols-7 gap-1 mb-2';
            diasSemana.forEach(dia => {
                const divDia = document.createElement('div');
                divDia.className = 'text-xs font-medium text-gray-500 text-center p-1';
                divDia.textContent = dia;
                encabezados.appendChild(divDia);
            });
            divMes.appendChild(encabezados);
            
            // Grid de días
            const gridDias = document.createElement('div');
            gridDias.className = 'grid grid-cols-7 gap-1';
            
            // Primer día del mes
            const primerDia = new Date(año, mes, 1);
            const ultimoDia = new Date(año, mes + 1, 0);
            
            // Días vacíos al inicio
            let diaSemanaInicio = primerDia.getDay();
            diaSemanaInicio = diaSemanaInicio === 0 ? 6 : diaSemanaInicio - 1; // Lunes = 0
            
            for (let i = 0; i < diaSemanaInicio; i++) {
                const divVacio = document.createElement('div');
                divVacio.className = 'w-8 h-8';
                gridDias.appendChild(divVacio);
            }
            
            // Días del mes
            for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
                const fecha = new Date(año, mes, dia);
                const fechaStr = fecha.toISOString().split('T')[0];
                const asistencia = asistenciasPorFecha[fechaStr];
                const esFindeSemana = fecha.getDay() === 0 || fecha.getDay() === 6;
                
                const divDia = crearDiaCalendario(fecha, asistencia, esFindeSemana);
                gridDias.appendChild(divDia);
            }
            
            divMes.appendChild(gridDias);
            contenedorCalendario.appendChild(divMes);
        }
    }

    // Función para crear vista por períodos
    function crearVistaPeriodos() {
        contenedorCalendario.innerHTML = '';
        
        periodosData.forEach(stat => {
            const periodo = stat.periodo;
            const fechaInicio = new Date(periodo.fecha_inicio);
            const fechaFin = new Date(periodo.fecha_fin);
            
            const divPeriodo = document.createElement('div');
            divPeriodo.className = 'bg-white rounded-xl shadow-sm border border-gray-200 p-6';
            divPeriodo.dataset.periodo = periodo.id_periodo;
            
            // Título del período
            const tituloPeriodo = document.createElement('h3');
            tituloPeriodo.className = 'text-lg font-semibold text-gray-900 mb-2';
            tituloPeriodo.textContent = periodo.nombre;
            divPeriodo.appendChild(tituloPeriodo);
            
            // Fechas del período
            const fechasPeriodo = document.createElement('p');
            fechasPeriodo.className = 'text-sm text-gray-600 mb-4';
            fechasPeriodo.textContent = `${fechaInicio.toLocaleDateString('es-ES')} - ${fechaFin.toLocaleDateString('es-ES')}`;
            divPeriodo.appendChild(fechasPeriodo);
            
            // Estadísticas del período
            const estadisticas = document.createElement('div');
            estadisticas.className = 'grid grid-cols-4 gap-4 mb-6';
            
            ['Presente', 'Ausente', 'Justificado', 'Tarde'].forEach(estado => {
                const divEstat = document.createElement('div');
                divEstat.className = 'text-center p-3 rounded-lg';
                
                const coloresFondo = {
                    'Presente': 'bg-green-50',
                    'Ausente': 'bg-red-50',
                    'Justificado': 'bg-yellow-50',
                    'Tarde': 'bg-orange-50'
                };
                
                const coloresTexto = {
                    'Presente': 'text-green-600',
                    'Ausente': 'text-red-600',
                    'Justificado': 'text-yellow-600',
                    'Tarde': 'text-orange-600'
                };
                
                divEstat.className += ` ${coloresFondo[estado]}`;
                
                const numero = document.createElement('div');
                numero.className = `text-2xl font-bold ${coloresTexto[estado]}`;
                numero.textContent = stat.totales[estado] || 0;
                
                const label = document.createElement('div');
                label.className = 'text-sm text-gray-700';
                label.textContent = estado;
                
                const porcentaje = document.createElement('div');
                porcentaje.className = `text-xs ${coloresTexto[estado]} mt-1`;
                porcentaje.textContent = `${stat.porcentajes[estado] || 0}%`;
                
                divEstat.appendChild(numero);
                divEstat.appendChild(label);
                divEstat.appendChild(porcentaje);
                estadisticas.appendChild(divEstat);
            });
            
            divPeriodo.appendChild(estadisticas);
            
            // Calendario del período
            const calendarioPeriodo = document.createElement('div');
            calendarioPeriodo.className = 'space-y-4';
            
            // Agrupar días por mes dentro del período
            const diasPorMes = {};
            const fechaActual = new Date(fechaInicio);
            
            while (fechaActual <= fechaFin) {
                const mesAño = `${fechaActual.getFullYear()}-${fechaActual.getMonth()}`;
                if (!diasPorMes[mesAño]) {
                    diasPorMes[mesAño] = [];
                }
                diasPorMes[mesAño].push(new Date(fechaActual));
                fechaActual.setDate(fechaActual.getDate() + 1);
            }
            
            Object.entries(diasPorMes).forEach(([mesAño, dias]) => {
                const [año, mes] = mesAño.split('-').map(Number);
                const nombreMes = new Date(año, mes).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
                
                const divMes = document.createElement('div');
                divMes.className = 'border border-gray-200 rounded-lg p-4';
                
                const tituloMes = document.createElement('h4');
                tituloMes.className = 'font-medium text-gray-900 mb-3 capitalize';
                tituloMes.textContent = nombreMes;
                divMes.appendChild(tituloMes);
                
                const gridDias = document.createElement('div');
                gridDias.className = 'flex flex-wrap gap-1';
                
                dias.forEach(fecha => {
                    const fechaStr = fecha.toISOString().split('T')[0];
                    const asistencia = asistenciasPorFecha[fechaStr];
                    const esFindeSemana = fecha.getDay() === 0 || fecha.getDay() === 6;
                    
                    const divDia = crearDiaCalendario(fecha, asistencia, esFindeSemana);
                    gridDias.appendChild(divDia);
                });
                
                divMes.appendChild(gridDias);
                calendarioPeriodo.appendChild(divMes);
            });
            
            divPeriodo.appendChild(calendarioPeriodo);
            contenedorCalendario.appendChild(divPeriodo);
        });
    }

    // Función para mostrar información del día
    function mostrarInformacionDia(fecha, asistencia) {
        const panel = document.getElementById('panel-informacion');
        const titulo = document.getElementById('titulo-informacion');
        const contenido = document.getElementById('contenido-informacion');
        
        titulo.textContent = fecha.toLocaleDateString('es-ES', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        // Encontrar el período correspondiente usando los datos del asistencia
        let periodoInfo = null;
        if (asistencia && asistencia.periodo) {
            periodoInfo = asistencia.periodo;
        }
        
        const colorBg = obtenerColorEstado(asistencia.estado).replace('bg-', 'bg-').replace('-500', '-50');
        const colorBorder = obtenerColorEstado(asistencia.estado).replace('bg-', '').replace('-500', '-200');
        
        contenido.innerHTML = `
            <div class="space-y-4">
                <div class="flex items-center justify-center p-4 rounded-lg ${colorBg} border border-${colorBorder}">
                    <div class="w-6 h-6 ${obtenerColorEstado(asistencia.estado)} rounded mr-3"></div>
                    <span class="font-semibold text-lg">${asistencia.estado}</span>
                </div>
                
                ${periodoInfo ? `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-1">Período</h4>
                        <p class="text-sm text-gray-600">${periodoInfo.nombre}</p>
                    </div>
                ` : ''}
                
                ${asistencia.observacion ? `
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-1">Observación</h4>
                        <p class="text-sm text-gray-600">${asistencia.observacion}</p>
                    </div>
                ` : ''}
                
                ${asistencia.justificacion ? `
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-1">Justificación</h4>
                        <p class="text-sm text-gray-600">${asistencia.justificacion}</p>
                    </div>
                ` : ''}
                
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">Información del día</h4>
                    <div class="text-sm text-gray-600">
                        <p>Día de la semana: ${fecha.toLocaleDateString('es-ES', { weekday: 'long' })}</p>
                        <p>Semana del año: ${getWeekNumber(fecha)}</p>
                        <p>Fecha: ${fecha.toLocaleDateString('es-ES')}</p>
                    </div>
                </div>
            </div>
        `;
        
        panel.classList.remove('hidden');
    }

    // Función para cerrar el panel de información
    window.cerrarPanelInformacion = function() {
        document.getElementById('panel-informacion').classList.add('hidden');
    };

    // Función para obtener el número de semana del año
    function getWeekNumber(date) {
        const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
        const dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    }

    // Función para aplicar filtros
    function aplicarFiltros() {
        const periodoSeleccionado = filtros.periodo.value;
        const estadoSeleccionado = filtros.estado.value;
        const añoSeleccionado = filtros.año.value;
        
        // Filtrar períodos
        document.querySelectorAll('[data-periodo]').forEach(elemento => {
            let mostrar = true;
            
            if (periodoSeleccionado && elemento.dataset.periodo !== periodoSeleccionado) {
                mostrar = false;
            }
            
            elemento.style.display = mostrar ? 'block' : 'none';
        });
        
        // Filtrar días individuales
        document.querySelectorAll('[data-fecha]').forEach(dia => {
            let mostrar = true;
            const fecha = new Date(dia.dataset.fecha);
            
            // Filtro por año
            if (añoSeleccionado && fecha.getFullYear().toString() !== añoSeleccionado) {
                mostrar = false;
            }
            
            // Filtro por estado
            if (estadoSeleccionado && dia.dataset.estado !== estadoSeleccionado) {
                mostrar = false;
            }
            
            // Filtro de fines de semana
            if (dia.dataset.finSemana === 'true' && !mostrarFinesSemana.checked) {
                mostrar = false;
            }
            
            dia.style.display = mostrar ? 'flex' : 'none';
        });
    }

    // Función para actualizar vista
    function actualizarVista() {
        const tipoVista = filtros.vistaTipo.value;
        const añoSeleccionado = filtros.año.value || new Date().getFullYear();
        
        if (tipoVista === 'año') {
            crearCalendarioAnual(parseInt(añoSeleccionado));
        } else {
            crearVistaPeriodos();
        }
        
        setTimeout(aplicarFiltros, 100);
    }

    // Event listeners
    filtros.vistaTipo.addEventListener('change', actualizarVista);
    filtros.año.addEventListener('change', actualizarVista);
    filtros.periodo.addEventListener('change', aplicarFiltros);
    filtros.estado.addEventListener('change', aplicarFiltros);
    mostrarFinesSemana.addEventListener('change', aplicarFiltros);
    
    limpiarFiltros.addEventListener('click', function() {
        Object.values(filtros).forEach(filtro => {
            if (filtro.id !== 'vista-calendario') {
                filtro.value = filtro.id === 'filtro-año' ? new Date().getFullYear() : '';
            }
        });
        mostrarFinesSemana.checked = false;
        actualizarVista();
    });

    // Cerrar panel al hacer clic fuera
    document.getElementById('panel-informacion').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarPanelInformacion();
        }
    });

    // Función para exportar calendario
    window.exportarCalendario = function() {
        const datos = [];
        
        Object.entries(asistenciasPorFecha).forEach(([fecha, asistencia]) => {
            const fechaObj = new Date(fecha);
            
            datos.push({
                fecha: fechaObj.toLocaleDateString('es-ES'),
                diaSemana: fechaObj.toLocaleDateString('es-ES', { weekday: 'long' }),
                periodo: asistencia.periodo ? asistencia.periodo.nombre : 'Sin período',
                estado: asistencia.estado,
                observacion: asistencia.observacion || '',
                justificacion: asistencia.justificacion || ''
            });
            console.log('Procesando asistencia para exportar:',datos[datos.length - 1]);
        });
        
        // Ordenar por fecha
        datos.sort((a, b) => new Date(a.fecha.split('/').reverse().join('-')) - new Date(b.fecha.split('/').reverse().join('-')));
        
        // Crear CSV
        let csv = 'Fecha,Día de la Semana,Período,Estado,Observación,Justificación\n';
        datos.forEach(fila => {
            csv += `"${fila.fecha}","${fila.diaSemana}","${fila.periodo}","${fila.estado}","${fila.observacion}","${fila.justificacion}"\n`;
        });
        
        // Descargar archivo
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `asistencias_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    };

    // Atajos de teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarPanelInformacion();
        }
        
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
        
        if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
            e.preventDefault();
            exportarCalendario();
        }
    });

    // Inicialización
    inicializarAños();
    actualizarVista();
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .bg-gray-50 {
        background: white !important;
    }
    
    .shadow-sm, .shadow-2xl {
        box-shadow: none !important;
    }
    
    .border {
        border: 1px solid #d1d5db !important;
    }
    
    .rounded-xl {
        border-radius: 8px !important;
    }
    
    #panel-informacion {
        display: none !important;
    }
    
    .fixed {
        position: relative !important;
    }
    
    .grid {
        display: grid !important;
        break-inside: avoid;
    }
    
    .space-y-8 > * + * {
        margin-top: 2rem !important;
    }
    
    .space-y-4 > * + * {
        margin-top: 1rem !important;
    }
}

@media (max-width: 640px) {
    .grid-cols-7 {
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 2px;
    }
    
    .w-8.h-8 {
        width: 1.5rem;
        height: 1.5rem;
        font-size: 0.6rem;
    }
    
    .grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .grid.grid-cols-1.md\\:grid-cols-4 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

.dia-calendario:hover {
    transform: scale(1.1);
    z-index: 10;
    position: relative;
}

.transition-all {
    transition: all 0.2s ease-in-out;
}

/* Animaciones para los días */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.dia-calendario {
    animation: fadeIn 0.3s ease-out;
}

/* Efectos de hover mejorados */
.dia-calendario:not(.bg-gray-100):hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: scale(1.15) translateY(-2px);
}

/* Indicadores de estado mejorados */
.bg-green-500 {
    background: linear-gradient(135deg, #10b981, #059669);
}

.bg-red-500 {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.bg-yellow-500 {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.bg-orange-500 {
    background: linear-gradient(135deg, #f97316, #ea580c);
}
</style>
@endsection