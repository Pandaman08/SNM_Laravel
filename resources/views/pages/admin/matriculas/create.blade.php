@extends('layout.admin.plantilla')

@section('title', 'Matricular')

@section('contenido')
    <div class="paper-container">
        <div class="main-title flex flex-col items-center gap-3 mb-8">
            <div class="title text-2xl font-semibold text-[#2e5382]">Matricular</div>
            <div class="blue-line w-1/5 h-0.5 bg-[#64d423]"></div>
        </div>

        <form class="p-10" action="{{ route('papers.store') }}" method="post" id="form" enctype="multipart/form-data">
            @csrf

            <!-- Contenedor principal en 2 columnas -->
            <div class="grid md:grid-cols-2 gap-6 bg-slate-200 p-6 rounded-lg">

                <!-- COLUMNA IZQUIERDA -->
                <div class="flex flex-col gap-6">
                    <!-- TÍTULO -->
                    <div>
                        <label for="titulo" class="block mb-2 text-sm font-medium text-gray-900 align-baseline">
                            Titulo
                        </label>
                        <input type="text" name="titulo" id="titulo"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                   focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Titulo del paper" required />
                    </div>

                    <!-- AUTORES -->
                    <div>
                        <label for="autores" class="block mb-2 text-sm font-medium text-gray-900">Autores</label>
                        <div id="authors-container" class="space-y-2">
                            <input type="text" id="new-author"
                                class="author-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                       focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Nombre del autor" />
                            <!-- Input oculto para enviar los autores ingresados -->
                            <input type="hidden" name="autores" id="autores-json" />
                        </div>
                        <div id="dropdownSearch" class="z-20 hidden bg-white rounded-lg shadow w-60 absolute mt-8">
                            <div class="p-3">
                                <ul id="authors-list" class="h-auto px-3 pb-3 overflow-y-auto text-sm text-gray-700"
                                    aria-labelledby="dropdownSearchButton">
                                </ul>
                                <button id="remove-authors-btn"
                                    class="flex items-center p-3 px-10 text-sm font-medium text-red-600 border-t border-gray-200 rounded-b-lg bg-gray-50 hover:bg-gray-100">
                                    <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 20 18">
                                        <path
                                            d="M6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Zm11-3h-6a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2Z" />
                                    </svg>
                                    Remover autor
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" id="add-author-btn"
                                class="mt-2 text-sm font-medium text-blue-500 hover:underline">
                                + Añadir autor
                            </button>
                            <button type="button" id="show-authors-btn"
                                class="mt-2 px-4 text-sm font-medium text-green-500 hover:underline">
                                Mostrar Autores
                            </button>
                        </div>
                    </div>

                    <!-- FECHA DE PUBLICACIÓN -->
                    <div>
                        <label for="fecha_publicacion" class="block mb-2 text-sm font-medium text-gray-900">
                            Fecha Publicación
                        </label>
                        <input type="date" name="fecha_publicacion" id="fecha_publicacion"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                   focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required />
                    </div>

                    <!-- TÓPICOS -->
                    <div>
                        <div class="relative">
                            <label for="topicos" class="block mb-2 text-sm font-medium text-gray-900">Tópicos</label>
                            <!-- Botón que activa el menú -->
                            <button type="button" id="toggle-menu"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                       focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-left">
                                Selecciona Tópicos
                            </button>
                            <!-- Menú desplegable flotante -->
                            <div id="topicos-menu"
                                class="absolute z-10 w-full bg-white border border-gray-300 shadow-md rounded-lg hidden">
                                <div class="max-h-48 overflow-y-auto p-2">
                                    @if (!$topicos->isEmpty())
                                        @foreach ($topicos as $topico)
                                            <label class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-md">
                                                <input type="checkbox" value="{{ $topico->id }}" class="checkbox-topico">
                                                <span>{{ $topico->nombre }}</span>
                                            </label>
                                        @endforeach
                                    @else
                                        <label class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-md">
                                            <span>No hay topicos registrados</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                            <!-- Input oculto para enviar los IDs de los tópicos seleccionados -->
                            <input type="hidden" name="topicos" id="topicos">
                        </div>
                    </div>

                    <!-- ABSTRACT -->
                    <div>
                        <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900">
                            Abstract
                            (<span class="text-sm text-green-500 border focus:outline-none p-2 mt-2" id="char-count">1000
                                caracteres restantes</span>)
                        </label>
                        <textarea
                            class="block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding
                                   border border-solid border-gray-300 rounded transition ease-in-out m-0
                                   focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                            id="descripcion" name="descripcion" rows="8" placeholder="Descripción" required></textarea>
                    </div>
                </div>

                <!-- COLUMNA DERECHA -->
                <div class="flex flex-col gap-6">
                    <!-- DOI -->
                    <div>
                        <label for="doi" class="block mb-2 text-sm font-medium text-gray-900">DOI</label>
                        <input type="text" name="doi" id="doi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                   focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="DOI" required />
                    </div>

                    <!-- PUBLISHER -->
                    <div>
                        <label for="publisher" class="block mb-2 text-sm font-medium text-gray-900">Publisher</label>
                        <input type="text" name="publisher" id="publisher"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                   focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Nombre publisher" required />
                    </div>

                    <!-- ÁREA -->
                    <div>
                        <label for="area_id" class="block mb-2 text-sm font-medium text-gray-900">Área</label>
                        <select name="area_id" id="area"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                   focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                            <option value="" disabled selected>Selecciona un Area de Investigación</option>
                            @if (!$areas->isEmpty())
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                @endforeach
                            @else
                                <option value="none">No hay areas Registradas</option>
                            @endif
                        </select>
                    </div>

                    <!-- IMAGEN PAPER -->
                    <div>
                        <label for="img_filename" class="block mb-2 text-sm font-medium text-gray-900">
                            Imagen Paper
                        </label>
                        <div id="image-upload"
                            class="border-2 border-dashed border-gray-300 bg-white w-full h-[233px] flex flex-col
                                   items-center justify-center cursor-pointer relative text-center rounded-md"
                            onclick="document.getElementById('img_filename').click()" ondragover="handleDragOver(event)"
                            ondrop="handleDrop(event, 'img_filename')">
                            <!-- Placeholder (solo se ve si no hay img_filename) -->
                            <span id="image-placeholder" class="text-gray-500 flex flex-col items-center">
                                <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 20 16" aria-hidden="true">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                Selecciona o arrastra una imagen (png, jpeg, jpg, gif)
                            </span>
                            <!-- Vista previa -->
                            <img id="previewImage" src="" alt="Vista previa"
                                class="hidden w-full h-full object-cover rounded shadow mx-auto">
                            <!-- Botón eliminar img_filename -->
                            <button type="button" id="remove-image"
                                class="hidden absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full
                                       hover:bg-red-600 transition cursor-pointer"
                                onclick="removeImage(event)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0
                                               0116.138 21H7.862a2 2 0
                                               01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a2 2
                                               0 00-2-2H9a2 2 0 00-2 2v2m3 0h4" />
                                </svg>
                            </button>
                            <input type="file" id="img_filename" name="img_filename" class="hidden"
                                accept="image/png, image/jpeg, image/jpg" onchange="mostrarVistaPrevia(event)">
                        </div>
                    </div>

                    <!-- ARCHIVO PDF -->
                    <div>
                        <label for="pdf_filename" class="block mb-2 text-sm font-medium text-gray-900">
                            Archivo PDF
                        </label>
                        <div id="pdf_filename-upload"
                            class="border-2 border-dashed border-gray-300 bg-white w-full h-11 flex flex-col
                                   items-center justify-center cursor-pointer relative text-center rounded-md"
                            onclick="document.getElementById('pdf_filename').click()" ondragover="handleDragOver(event)"
                            ondrop="handleDrop(event, 'pdf_filename')">
                            <!-- Placeholder pdf_filename -->
                            <span id="pdf_filename-placeholder" class="text-gray-500">
                                Selecciona o arrastra un archivo PDF
                            </span>
                            <!-- Nombre de archivo pdf_filename -->
                            <div id="pdf_filename-file-info" class="hidden text-sm"></div>
                            <!-- Botón eliminar pdf_filename -->
                            <button type="button" id="remove-pdf_filename"
                                class="hidden absolute top-1 right-2 bg-red-500 text-white p-1 rounded-full
                                       hover:bg-red-600 transition cursor-pointer"
                                onclick="removepdf_filename(event)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0
                                               0116.138 21H7.862a2 2 0
                                               01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a2 2
                                               0 00-2-2H9a2 2 0 00-2 2v2m3 0h4" />
                                </svg>
                            </button>
                            <input type="file" id="pdf_filename" name="pdf_filename" class="hidden"
                                accept="application/pdf" onchange="mostrarpdf_filename(event)">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones finales -->
            <div class="flex justify-center gap-2 mt-10">
                <button type="submit"
                    class="focus:outline-none text-white bg-green-500 hover:bg-green-600 focus:ring-4
                           focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                    Guardar
                </button>
                <a href="{{ route('papers.create') }}"
                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4
                           focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 cursor-pointer">
                    Cancelar
                </a>
                <a href="{{ route('papers.index') }}"
                    class="focus:outline-none text-white bg-blue-500 hover:bg-blue-600 focus:ring-4
                           focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 cursor-pointer">
                    Volver
                </a>
            </div>
        </form>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Creado exitosamente!',
                text: "{{ session('success') }}",
                showConfirmButton: true,
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @elseif (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '¡Hubo un error!',
                html: "{!! session('error') !!}",
                showConfirmButton: true,
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'bg-red-500 text-white hover:bg-red-600 focus:ring-2 focus:ring-red-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @endif
@endsection

@section('script')
    <!-- Mismos scripts y funciones de vista previa / validaciones -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Lógica para Autores
            const authorsContainer = document.getElementById("authors-container");
            const authorsList = document.getElementById("authors-list");
            const addAuthorBtn = document.getElementById("add-author-btn");
            const showAuthorsBtn = document.getElementById("show-authors-btn");
            const dropdownSearch = document.getElementById("dropdownSearch");
            const removeAuthorsBtn = document.getElementById("remove-authors-btn");
            const autoresJsonInput = document.getElementById("autores-json");
            const form = document.getElementById("form");

            const addedAuthors = new Set();

            addAuthorBtn.addEventListener("click", () => {
                const authorInput = document.getElementById("new-author");
                const authorName = authorInput.value.trim();
                if (authorName && !addedAuthors.has(authorName)) {
                    addedAuthors.add(authorName);
                    const listItem = document.createElement("li");
                    listItem.innerHTML = `
                        <div class="flex items-center ps-2 rounded hover:bg-gray-100 w-full">
                          <input type="checkbox" class="author-checkbox w-4 h-4 text-blue-600 bg-gray-100
                                                     border-gray-300 rounded focus:ring-blue-500" value="${authorName}">
                          <label class="w-full py-2 ms-2 text-sm font-medium text-gray-900 rounded">
                            ${authorName}
                          </label>
                        </div>`;
                    authorsList.appendChild(listItem);
                    authorInput.value = "";
                }
            });

            document.addEventListener("click", function(event) {
                if (!dropdownSearch.contains(event.target) && !showAuthorsBtn.contains(event.target)) {
                    dropdownSearch.classList.add("hidden");
                }
            });

            showAuthorsBtn.addEventListener("click", () => {
                if (addedAuthors.size > 0) {
                    dropdownSearch.classList.toggle("hidden");
                }
            });

            removeAuthorsBtn.addEventListener("click", () => {
                const checkboxes = document.querySelectorAll(".author-checkbox:checked");
                checkboxes.forEach((checkbox) => {
                    const authorName = checkbox.value;
                    addedAuthors.delete(authorName);
                    checkbox.closest("li").remove();
                });
                if (addedAuthors.size === 0) {
                    dropdownSearch.classList.toggle("hidden");
                }
            });

            form.addEventListener("submit", (event) => {
                const authorsArray = Array.from(addedAuthors);
                autoresJsonInput.value = JSON.stringify(authorsArray);
            });

            // Contador de caracteres para Abstract
            const textarea = document.getElementById('descripcion');
            const charCount = document.getElementById('char-count');
            const maxChars = 1000;

            const updateCount = () => {
                const content = textarea.value;
                const remainingChars = maxChars - content.length;
                charCount.textContent = `${remainingChars} caracteres restantes`;
                if (remainingChars < 20) {
                    charCount.classList.add("text-red-500");
                } else {
                    charCount.classList.remove("text-red-500");
                }
            };

            textarea.addEventListener('input', updateCount);

            textarea.addEventListener('keydown', (event) => {
                const content = textarea.value;
                if (content.length >= maxChars &&
                    !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(
                        event.key)) {
                    event.preventDefault();
                }
            });

            textarea.addEventListener('paste', (event) => {
                const content = textarea.value;
                const clipboardText = (event.clipboardData || window.clipboardData).getData('text');
                const remainingChars = maxChars - content.length;
                if (clipboardText.length > remainingChars) {
                    event.preventDefault();
                    const truncatedText = clipboardText.substring(0, remainingChars);
                    textarea.value += truncatedText;
                }
                updateCount();
            });

            updateCount();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Manejo de Tópicos
            const toggleButton = document.getElementById("toggle-menu");
            const menu = document.getElementById("topicos-menu");
            const checkboxes = document.querySelectorAll(".checkbox-topico");
            const hiddenInput = document.getElementById("topicos");
            let selectedTopicos = [];

            toggleButton.addEventListener("click", function() {
                menu.classList.toggle("hidden");
            });

            document.addEventListener("click", function(event) {
                if (!toggleButton.contains(event.target) && !menu.contains(event.target)) {
                    menu.classList.add("hidden");
                }
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    const id = this.value;
                    if (this.checked) {
                        if (!selectedTopicos.includes(id)) {
                            selectedTopicos.push(id);
                        }
                    } else {
                        selectedTopicos = selectedTopicos.filter(t => t !== id);
                    }
                    hiddenInput.value = selectedTopicos.join(",");
                });
            });
        });

        // ---------- FUNCIONES DE VISTA PREVIA (img_filename) ----------
        function mostrarVistaPrevia(event) {
            const input = event.target;
            const previewImage = document.getElementById("previewImage");
            const imagePlaceholder = document.getElementById("image-placeholder");
            const removeBtn = document.getElementById("remove-image");

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert("Tipo de archivo no permitido. Solo se permiten png, jpeg, jpg, gif.");
                    input.value = "";
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    imagePlaceholder.classList.add('hidden');
                    removeBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage(event) {
            if (event) event.stopPropagation();
            const imageInput = document.getElementById("img_filename");
            imageInput.value = "";
            const previewImage = document.getElementById("previewImage");
            previewImage.src = "";
            previewImage.classList.add('hidden');
            document.getElementById("image-placeholder").classList.remove('hidden');
            document.getElementById("remove-image").classList.add('hidden');
        }

        // ---------- FUNCIONES DE VISTA PREVIA (pdf_filename) ----------
        function mostrarpdf_filename(event) {
            const input = event.target;
            const pdf_filenamePlaceholder = document.getElementById("pdf_filename-placeholder");
            const pdf_filenameFileInfo = document.getElementById("pdf_filename-file-info");
            const removeBtn = document.getElementById("remove-pdf_filename");

            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.type !== 'application/pdf') {
                    alert("Tipo de archivo no permitido. Solo se permite PDF.");
                    input.value = "";
                    return;
                }
                pdf_filenameFileInfo.textContent = file.name;
                pdf_filenameFileInfo.classList.remove('hidden');
                pdf_filenamePlaceholder.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            }
        }

        function removepdf_filename(event) {
            if (event) event.stopPropagation();
            const pdf_filenameInput = document.getElementById("pdf_filename");
            pdf_filenameInput.value = "";
            document.getElementById("pdf_filename-file-info").textContent = "";
            document.getElementById("pdf_filename-file-info").classList.add('hidden');
            document.getElementById("pdf_filename-placeholder").classList.remove('hidden');
            document.getElementById("remove-pdf_filename").classList.add('hidden');
        }

        // ---------- DRAG & DROP ----------
        function handleDragOver(event) {
            event.preventDefault();
        }

        function handleDrop(event, inputId) {
            event.preventDefault();
            const inputElement = document.getElementById(inputId);
            if (event.dataTransfer.files && event.dataTransfer.files[0]) {
                inputElement.files = event.dataTransfer.files;
                if (inputId === 'img_filename') {
                    mostrarVistaPrevia({
                        target: inputElement
                    });
                } else {
                    mostrarpdf_filename({
                        target: inputElement
                    });
                }
            }
        }
    </script>
@endsection
