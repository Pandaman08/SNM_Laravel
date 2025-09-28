@extends('layout.admin.plantilla')

@section('titulo', 'Registrar Pago')

@section('contenido')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-2 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                            Registrar Nuevo Pago
                        </h1>
                        <p class="text-slate-500 text-sm">Complete los datos del pago y adjunte el comprobante</p>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/50 overflow-hidden">
                <form action="{{ route('pagos.store') }}" method="POST" class="p-8" enctype="multipart/form-data">
                    @csrf

                    <!-- Información de la Matrícula -->
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/50 rounded-xl p-6 mb-8">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="p-2 bg-emerald-500 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-emerald-800">Información de Matrícula</h3>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-emerald-100">
                            <label class="block text-sm font-medium text-emerald-700 mb-2">Código de Matrícula</label>
                            <input type="text" name="codigo_matricula" value="{{ $matricula->codigo_matricula }}"
                                class="w-full px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 font-mono font-semibold text-lg"
                                readonly>
                        </div>
                    </div>

                    <!-- Campos del formulario -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Columna izquierda -->
                        <div class="space-y-6">
                            <!-- Campo Monto -->
                            <div class="group">
                                <label for="monto" class="block text-sm font-semibold text-slate-700 mb-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="p-1 bg-amber-100 rounded-lg">
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span>Monto del Pago</span>
                                    </div>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500 font-semibold">S/</span>
                                    <input type="number" step="0.01" id="monto" name="monto" value="{{ old('monto') }}"
                                        class="w-full pl-12 pr-4 py-4 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 text-lg font-semibold group-hover:border-emerald-300"
                                        required placeholder="0.00">
                                </div>
                                @error('monto')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Campo Concepto -->
                            <div class="group">
                                <label for="concepto" class="block text-sm font-semibold text-slate-700 mb-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="p-1 bg-blue-100 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span>Concepto del Pago</span>
                                    </div>
                                </label>
                                <input type="text" id="concepto" name="concepto" value="{{ old('concepto') }}"
                                    class="w-full px-4 py-4 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 group-hover:border-emerald-300"
                                    required placeholder="Ej: Matrícula, Mensualidad, Materiales">
                                @error('concepto')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Campo Fecha de Pago -->
                            <div class="group">
                                <label for="fecha_pago" class="block text-sm font-semibold text-slate-700 mb-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="p-1 bg-purple-100 rounded-lg">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span>Fecha de Pago</span>
                                    </div>
                                </label>
                                <input type="date" id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}"
                                    class="w-full px-4 py-4 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 group-hover:border-emerald-300"
                                    required>
                                @error('fecha_pago')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Columna derecha - Comprobante -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">
                                <div class="flex items-center space-x-2">
                                    <div class="p-1 bg-indigo-100 rounded-lg">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <span>Comprobante de Pago</span>
                                </div>
                            </label>
                            
                            <!-- Vista previa del comprobante -->
                            <div id="previewContainer" class="hidden mb-6">
                                <div class="relative bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-4 border-2 border-dashed border-slate-200">
                                    <div class="relative group">
                                        <img id="previewImage" src="#" alt="Vista previa del comprobante"
                                            class="w-full h-64 object-cover rounded-xl shadow-lg transition-transform duration-200 group-hover:scale-[1.02]" />
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-200 rounded-xl"></div>
                                        <button type="button" id="removeImageBtn"
                                            class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-110">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <p class="text-sm font-medium text-slate-600">Comprobante cargado</p>
                                        <p class="text-xs text-slate-400">Haz clic en el botón × para cambiar la imagen</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Dropzone -->
                            <div id="dropzone" class="group">
                                <div class="relative border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center hover:border-emerald-400 hover:bg-emerald-50/50 transition-all duration-300 cursor-pointer group-hover:scale-[1.01]">
                                    <input id="comprobante_img" name="comprobante_img" type="file" class="hidden" accept="image/*">
                                    
                                    <div class="space-y-4">
                                        <div class="mx-auto w-16 h-16 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-2xl flex items-center justify-center group-hover:from-emerald-200 group-hover:to-teal-200 transition-all duration-300">
                                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                        </div>
                                        
                                        <div>
                                            <label for="comprobante_img" class="cursor-pointer">
                                                <span class="text-lg font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                                                    Subir comprobante
                                                </span>
                                            </label>
                                            <p class="text-slate-500 mt-1">o arrastra la imagen aquí</p>
                                        </div>
                                        
                                        <div class="flex items-center justify-center space-x-4 text-sm text-slate-400">
                                            <span class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>PNG, JPG, JPEG</span>
                                            </span>
                                            <span class="text-slate-300">•</span>
                                            <span>Máximo 5MB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('comprobante_img')
                                <p class="text-red-500 text-sm mt-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-4 pt-8 mt-8 border-t border-slate-200">
                        <a href="{{ route('pagos.index') }}"
                            class="px-6 py-3 bg-white border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 rounded-xl text-slate-700 font-semibold text-sm flex items-center space-x-2 transition-all duration-200 hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Cancelar</span>
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 rounded-xl text-white font-semibold text-sm flex items-center space-x-2 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:scale-105 hover:shadow-emerald-500/40">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Registrar Pago</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('comprobante_img');
            const dropzone = document.getElementById('dropzone');
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            const removeImageBtn = document.getElementById('removeImageBtn');

            // Función para mostrar la vista previa
            function showPreview(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        previewImage.src = event.target.result;
                        previewContainer.classList.remove('hidden');
                        dropzone.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Función para ocultar la vista previa
            function hidePreview() {
                previewImage.src = '#';
                previewContainer.classList.add('hidden');
                dropzone.classList.remove('hidden');
                input.value = '';
            }

            // Event listener para el input
            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    showPreview(file);
                }
            });

            // Event listener para el botón de remover
            removeImageBtn.addEventListener('click', function () {
                hidePreview();
            });

            // Drag and drop functionality
            dropzone.addEventListener('click', function() {
                input.click();
            });

            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropzone.classList.add('border-emerald-500', 'bg-emerald-50');
            });

            dropzone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropzone.classList.remove('border-emerald-500', 'bg-emerald-50');
            });

            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropzone.classList.remove('border-emerald-500', 'bg-emerald-50');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        input.files = files;
                        showPreview(file);
                    }
                }
            });
        });
    </script>
@endsection