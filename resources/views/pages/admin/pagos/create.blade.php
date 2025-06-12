@extends('layout.admin.plantilla')

@section('titulo', 'Registrar Pago')

@section('contenido')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="flex items-center mb-6">
            <svg class="w-8 h-8 text-[#98C560] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
            <h1 class="text-2xl font-semibold text-gray-800">Registrar Nuevo Pago</h1>
        </div>

        <form action="{{ route('pagos.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Campo Matrícula -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="codigo_matricula" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        Matrícula
                    </label>
                    <input id="codigo_matricula" name="codigo_matricula" value="{{ $matricula->codigo_matricula }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#98C560] focus:border-[#98C560] bg-gray-100">
                </div>

                <!-- Campo Monto -->
                <div>
                    <label for="monto" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        Monto (S/)
                    </label>
                    <input type="number" step="0.01" id="monto" name="monto" value="{{ old('monto') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#98C560] focus:border-[#98C560]"
                        required placeholder="0.00">
                    @error('monto')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Campo Concepto -->
            <div>
                <label for="concepto" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                        </path>
                    </svg>
                    Concepto
                </label>
                <input id="concepto" name="concepto" value="{{ old('concepto') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#98C560] focus:border-[#98C560]"
                    required placeholder="Ej: Matrícula, Mensualidad, Materiales">
                @error('concepto')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo Fecha de Pago -->
            <div>
                <label for="fecha_pago" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Fecha de Pago
                </label>
                <input type="date" id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('fecha_pago')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Campo Comprobante con vista previa -->
            <div>
                <label for="comprobante_img" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    ...
                    Comprobante
                </label>
                <div class="mt-1">
                    <!-- Contenedor para la vista previa -->
                    <div id="previewContainer" class="mb-4 hidden relative w-48">
                        <img id="previewImage" src="#" alt="Vista previa"
                            class="rounded-lg shadow-md object-cover w-full h-auto" />
                        <button type="button" id="removeImageBtn"
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs hover:bg-red-600">✕</button>
                    </div>

                    <!-- Dropzone -->
                    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            ...
                            <div class="flex text-sm text-gray-600">
                                <label for="comprobante_img"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-[#98C560] hover:text-[#7aa94f] focus-within:outline-none">
                                    <span>Subir archivo</span>
                                    <input id="comprobante_img" name="comprobante_img" type="file" class="sr-only"
                                        accept="image/*">
                                </label>
                                <p class="pl-1">o arrastrar aquí</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG hasta 5MB</p>
                        </div>
                    </div>
                </div>
                @error('comprobante_img')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Campo Estado Oculto -->


            <!-- Botones -->
            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('pagos.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Cancelar
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-[#98C560] hover:bg-[#7aa94f] rounded-md text-white text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Registrar Pago
                </button>
            </div>
        </form>
    </div>

    <script>
    const input = document.getElementById('comprobante_img');
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('previewImage');
    const removeImageBtn = document.getElementById('removeImageBtn');

    input.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (event) {
                previewImage.src = event.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
        }
    });

    removeImageBtn.addEventListener('click', function () {
        input.value = '';
        previewImage.src = '#';
        previewContainer.classList.add('hidden');
    });
</script>
@endsection
