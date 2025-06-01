@extends('layout.admin.plantilla')

@section('title', 'Gestión de Personas')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">
        
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#2e5382]">Tutores por aprobar</h1>
            <div class="w-1/4 mx-auto h-0.5 bg-[#64d423]"></div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-200 text-gray-700 uppercase">
                    <tr>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Apellido</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Parentesco</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Dirección</th>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $user->persona->name }}</td>
                            <td class="px-4 py-3">{{ $user->persona->lastname }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->tutor->parentesco }}</td>
                            <td class="px-4 py-3">{{ $user->persona->phone }}</td>
                            <td class="px-4 py-3">{{ $user->persona->address }}</td>
                            <td class="px-4 py-3">
                                @if ($user->persona->photo)
                                <div class="px-8 py-0.1 text-center">
                                    <button 
                                        class="w-8 h-8 flex items-center justify-start rounded shadow cursor-pointer"
                                        onclick="openModal('{{ Storage::url($user->persona->photo) }}', 'image')"
                                    >
                                        <svg 
                                            xmlns="http://www.w3.org/2000/svg" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            stroke-width="2" 
                                            stroke-linecap="round" 
                                            stroke-linejoin="round" 
                                            class="w-6 h-6"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M18 22H4a2 2 0 0 1-2-2V6"/>
                                            <path d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18"/>
                                            <circle cx="12" cy="8" r="2"/>
                                            <rect width="16" height="16" x="6" y="2" rx="2"/>
                                        </svg>
                                    </button>
                                </div>
                                @else
                                    <span>No hay foto</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                @if (!$user->estado)
                                    <form action="{{ route('person.approve', $user->user_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-green-500 hover:text-green-700">Aprobar</button>
                                    </form>
                                @else
                                    <span class="text-green-500">Aprobado</span>
                                @endif
                                <form action="{{ route('person.destroy_person', $user->user_id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end text-sm mt-4">
            {{ $users->links('pagination::tailwind') }}
        </div>
    </div>

    <div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-7 rounded shadow-lg max-w-7xl w-full relative">
            <button class="absolute top-0.5 right-0.5 text-gray-500 hover:text-black text-3xl p-2" onclick="closeModal()">×</button>
            <div id="modalContent"></div>
        </div>
    </div>

    @if (session('success-approve'))
    <script>
        Swal.fire({
            title: "Aprobado Exitosamente!",
            text: "{{ session('success-approve') }}",
            icon: "success",
            customClass: {
                confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
            }
        });
    </script>
      @elseif (session('success-destroy'))
      <script>
          Swal.fire({
              title: "Eliminado!",
              text: "{{ session('success-destroy') }}",
              icon: "success",
              customClass: {
                  confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
              }
          });
      </script>
@endif

<script>

    function openModal(imageUrl, type) {
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `<img src="${imageUrl}" class="w-full max-h-[75vh] object-contain">`;
    document.getElementById('archivoModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('archivoModal').classList.add('hidden');
}

</script>

@endsection
