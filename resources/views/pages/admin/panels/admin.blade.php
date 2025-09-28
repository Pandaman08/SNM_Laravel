@extends('layout.admin.plantilla')

@section('titulo', 'Panel de Control - Colegio Brunning')

@section('contenido')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Panel de Administración</h1>
                <p class="text-gray-600">Colegio Brunning - Gestión Académica</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="font-medium">Bienvenido, <span class="text-blue-600">{{ Auth::user()->persona->name }}</span>
                    </p>
                    <p class="text-sm text-gray-500">{{ now()->format('d M Y, H:i') }}</p>
                </div>
                <img class="w-12 h-12 rounded-full border-2 border-white shadow-md"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->persona->name) }}&background=4f46e5&color=fff"
                    alt="Usuario">
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Estadísticas rápidas -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="ri-user-3-fill text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Estudiantes Activos</p>
                    <p class="text-2xl font-bold">
                        {{ $matriculas->filter(function ($m) {return $m->estado == 'activo';})->count() }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="ri-file-list-3-fill text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Matrículas Hoy</p>
                    <p class="text-2xl font-bold">
                        {{ $matriculas->filter(function ($m) {
                                return \Carbon\Carbon::parse($m->fecha)->isToday();
                            })->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Módulos Principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 text-white">
                        <h2 class="text-xl font-bold">Acciones Rápidas</h2>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                        <a href="{{ route('matriculas.index') }}"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-blue-50 transition-colors">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mb-2">
                                <i class="ri-user-add-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Panel Matrícula</span>
                        </a>
                        <a href="{{ route('docentes.buscar') }}"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-green-50 transition-colors">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 mb-2">
                                <i class="ri-user-add-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Panel Docentes</span>
                        </a>
                        <a href="{{ route('secretarias.buscar') }}"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-purple-50 transition-colors">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mb-2">
                                <i class="ri-line-chart-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Panel Tesoreros</span>
                        </a>
                        <a href="{{ route('pagos.index') }}"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-amber-50 transition-colors">
                            <div class="p-3 rounded-full bg-amber-100 text-amber-600 mb-2">
                                <i class="ri-money-dollar-circle-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Pagos</span>
                        </a>
                        <a href="{{ route('estudiantes.buscar') }}"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-red-50 transition-colors">
                            <div class="p-3 rounded-full bg-red-100 text-red-600 mb-2">
                                <i class="ri-notification-3-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Panel estudiantes </span>
                        </a>
                        <a href="{{ route('users.edit_user') }}"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-indigo-50 transition-colors">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mb-2">
                                <i class="ri-settings-4-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Configuración</span>
                        </a>
                    </div>
                </div>

                  <!-- Últimas Actividades -->
                   <div class="bg-white rounded-xl shadow-md overflow-hidden">
                      <div class="bg-gradient-to-r from-gray-700 to-gray-900 p-4 flex justify-between items-center">
                         <h2 class="text-xl font-bold text-white"> Gráfico de Matrículas</h2>

                         <!-- Filtro -->
                          <form method="GET" action="{{ route('matriculas.reporte') }}" class="flex items-center space-x-2">
                            <label for="nivel" class="font-medium text-sm text-gray-200">Filtrar por:</label>
                             <select name="nivel" id="nivel" onchange="this.form.submit()"
                              class="bg-white text-gray-800 border border-gray-300 rounded-lg px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                               <option value="">-- Todos --</option>
                                <option value="Inicial" {{ ($nivel ?? '') == 'Inicial' ? 'selected' : '' }}>Inicial</option>
                                <option value="Primaria" {{ ($nivel ?? '') == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                                <option value="Secundaria" {{ ($nivel ?? '') == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                            </select>
                         </form>
                        </div>

                    <div class="row g-4 mb-4">
                        <!-- Gráfico por Sección -->
                        <div class="col-md-6">
                            <div class="card shadow-sm rounded-4">
                                <div class="card-body">
                                    <h5 class="card-title text-center mb-3 fw-semibold">Matriculados por Grado y Sección
                                    </h5>
                                    <div id="chartSeccion" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Panel Derecho -->
            <div class="space-y-6">
                <!-- Calendario Académico -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 p-4 text-white">
                        <h2 class="text-xl font-bold">Calendario</h2>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-4">
                            <button class="p-2 rounded-full hover:bg-gray-100">
                                <i class="ri-arrow-left-s-line"></i>
                            </button>
                            <span class="font-medium">Junio 2023</span>
                            <button class="p-2 rounded-full hover:bg-gray-100">
                                <i class="ri-arrow-right-s-line"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-sm mb-2">
                            <div class="font-medium text-gray-400">L</div>
                            <div class="font-medium text-gray-400">M</div>
                            <div class="font-medium text-gray-400">M</div>
                            <div class="font-medium text-gray-400">J</div>
                            <div class="font-medium text-gray-400">V</div>
                            <div class="font-medium text-gray-400">S</div>
                            <div class="font-medium text-gray-400">D</div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center">
                            <!-- Días del mes (ejemplo simplificado) -->
                            <div class="p-2 text-gray-400">29</div>
                            <div class="p-2 text-gray-400">30</div>
                            <div class="p-2 text-gray-400">31</div>
                            <div class="p-2">1</div>
                            <div class="p-2">2</div>
                            <div class="p-2">3</div>
                            <div class="p-2">4</div>
                            <!-- Segunda fila -->
                            <div class="p-2">5</div>
                            <div class="p-2">6</div>
                            <div class="p-2">7</div>
                            <div class="p-2">8</div>
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600 font-medium">9</div>
                            <div class="p-2">10</div>
                            <div class="p-2">11</div>
                            <!-- Tercera fila -->
                            <div class="p-2">12</div>
                            <div class="p-2">13</div>
                            <div class="p-2 rounded-full bg-red-100 text-red-600 font-medium">14</div>
                            <div class="p-2">15</div>
                            <div class="p-2">16</div>
                            <div class="p-2">17</div>
                            <div class="p-2">18</div>
                            <!-- Cuarta fila -->
                            <div class="p-2">19</div>
                            <div class="p-2">20</div>
                            <div class="p-2">21</div>
                            <div class="p-2 rounded-full bg-green-100 text-green-600 font-medium">22</div>
                            <div class="p-2">23</div>
                            <div class="p-2">24</div>
                            <div class="p-2">25</div>
                            <!-- Quinta fila -->
                            <div class="p-2">26</div>
                            <div class="p-2">27</div>
                            <div class="p-2">28</div>
                            <div class="p-2">29</div>
                            <div class="p-2">30</div>
                            <div class="p-2 text-gray-400">1</div>
                            <div class="p-2 text-gray-400">2</div>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                            <span class="text-sm">Reunión de padres</span>
                        </div>
                        <div class="flex items-center mb-3">
                            <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                            <span class="text-sm">Entrega de notas</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-sm">Evento escolar</span>
                        </div>
                    </div>
                </div>

                <!-- Accesos Directos -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-600 to-teal-800 p-4 text-white">
                        <h2 class="text-xl font-bold">Estado Matriculas</h2>
                    </div>
                    <div class="p-4 space-y-3">
                        <!-- Gráfico por Estado -->
                        <div class="col-md-6">
                            <div class="card shadow-sm rounded-4">
                                <div class="card-body">
                                    <div id="chartEstado" style="height: 380px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Sistema de Gestión Académica - Colegio Brunning © {{ date('Y') }}</p>
            <p class="mt-1">Versión 2.1.0 </p>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Gráfico por sección (Grafico de barras)
        var optionsSeccion = {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [{
                name: 'Cantidad de Alumnos',
                data: {!! json_encode($matriculasPorSeccion->isNotEmpty() ? $matriculasPorSeccion->pluck('total') : []) !!}
            }],


            xaxis: {
                categories: {!! json_encode(
                    $matriculasPorSeccion->isNotEmpty()
                        ? $matriculasPorSeccion->map(fn($m) => $m->seccion?->nombre_completo ?? 'Sin sección')
                        : [],
                ) !!},
                labels: {
                    style: {
                        fontSize: '10px',
                        fontWeight: 500
                    },
                    rotate: -25,
                    trim: false // evita que se recorten
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '50%',
                    distributed: true
                }
            },
            colors: ['#1E90FF', '#FF7F50', '#32CD32', '#FFD700', '#8A2BE2', '#FF69B4'],
            legend: {
                show: false
            }
        };


        new ApexCharts(document.querySelector("#chartSeccion"), optionsSeccion).render();

        // Gráfico por estado 
        var optionsEstado = {
            chart: {
                type: 'donut',
                height: 380
            },
            series: {!! json_encode($matriculasPorEstado->isNotEmpty() ? $matriculasPorEstado->pluck('total') : []) !!},
            labels: {!! json_encode($matriculasPorEstado->isNotEmpty() ? $matriculasPorEstado->pluck('estado') : []) !!},
            colors: ['#e15f0eff', '#26cd00ff', '#FFCE56', '#C9CBCE'],
            legend: {
                position: 'bottom'
            }
        };
        new ApexCharts(document.querySelector("#chartEstado"), optionsEstado).render();
    </script>
@endsection
