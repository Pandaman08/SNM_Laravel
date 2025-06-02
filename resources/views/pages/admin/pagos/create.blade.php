@extends('layout.admin.plantilla')

@section('titulo', 'Registrar Pago')

@section('contenido')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Registrar Nuevo Pago</h1>

        <form action="{{ route('pagos.store') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
            @csrf

            <div>
                <label for="codigo_matricula" class="block text-sm font-medium text-gray-700">Matrícula</label>
                <select id="codigo_matricula" name="codigo_matricula" required
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]">
                    <option value="">Seleccione una matrícula</option>
                    @foreach($matriculas as $matricula)
                        <option value="{{ $matricula->codigo_matricula }}" {{ old('codigo_matricula') == $matricula->codigo_matricula ? 'selected' : '' }}>
                            {{ $matricula->estudiante->nombre_completo }} - {{ $matricula->codigo_matricula }}
                        </option>
                    @endforeach
                </select>
                @error('codigo_matricula')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="concepto" class="block text-sm font-medium text-gray-700">Concepto</label>
                <input id="concepto" name="concepto" value="{{ old('concepto') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required placeholder="Ej: Matrícula, Mensualidad, Materiales">
                @error('concepto')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="monto" class="block text-sm font-medium text-gray-700">Monto (S/)</label>
                <input type="number" step="0.01" id="monto" name="monto" value="{{ old('monto') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required placeholder="0.00">
                @error('monto')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="fecha_pago" class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                <input type="date" id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('fecha_pago')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                      <input type="text" step="0.01" id="estado" name="estado" value="Pendiente"
                    class=" hidden w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required placeholder="0.00">
            </div>

            <div>
                <label for="comprobante_img" class="block text-sm font-medium text-gray-700">Comprobante (Opcional)</label>
                <input type="file" id="comprobante_img" name="comprobante_img"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]">
                @error('comprobante_img')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('pagos.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-[#98C560] hover:bg-[#7aa94f] rounded-md text-white text-sm">Registrar</button>
            </div>
        </form>
    </div>
@endsection