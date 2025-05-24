<form id="upload-files" class="w-full md:w-1/2 mx-auto"
      action="{{ isset($product->file_edition) ? route('client.file.update') : route('client.file.store') }}"
      method="post" enctype="multipart/form-data">
    @csrf
    @method(isset($product->file_edition) ? 'PUT' :'POST')
    <x-forms.file-input
            :multiple="true"
            :error="$errors->has('files') ? $errors->first('files') : null"
    />
    <input type="hidden" name="product" id="product" value="{{ $product->id }}">
    <div class="w-full md:w-fit ms-auto mt-4">
        <x-forms.submit-button btn_color="btn-success">
            {{ __('products.upload_files') }}
        </x-forms.submit-button>
    </div>
</form>
@push('scripts')
    <script type="application/javascript">
        let selectedFiles = [];

        function displayFiles(e) {
            const newFiles = Array.from(e.target.files);
            const form = document.querySelector('#upload-files') || document.querySelector('form');

            selectedFiles = [...selectedFiles, ...newFiles];

            if (!document.querySelector('#preview-pdf')) {
                const div = document.createElement('div');
                div.setAttribute('id', 'preview-pdf');
                div.className = 'mt-5 flex flex-wrap items-center justify-center gap-10';
                form.insertAdjacentElement('afterend', div);
            }

            const previewContainer = document.querySelector('#preview-pdf');
            previewContainer.innerHTML = '';

            const dataTransfer = new DataTransfer();

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                const fileId = `file-${index}-${Math.random().toString(36).substring(7)}`;

                reader.onload = function(e) {
                    const fileContainer = document.createElement('div');
                    fileContainer.className = 'w-full md:w-80 border rounded shadow border-gray-700 p-4 transition-all duration-300 hover:shadow-xl group';
                    fileContainer.id = `container-${fileId}`;

                    const pdf = document.createElement('object');
                    pdf.className = 'aspect-square w-full mb-3.5 max-w-[250px] mx-auto';
                    pdf.data = e.target.result;
                    pdf.type = 'application/pdf';

                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'space-y-2';

                    const iconTemplate = document.createElement('template');
                    iconTemplate.innerHTML = `<x-icons.file-icon class="h-6 w-6 text-gray-50 group-hover:text-gray-300 transition-all duration-300"></x-icons.file-icon>`;
                    
                    fileInfo.innerHTML = `
                        <div class="flex items-center space-x-3">
                            ${iconTemplate.innerHTML}
                            <span class="text-lg font-medium text-gray-50 transition-all duration-300 group-hover:text-gray-300">${file.name}</span>
                        </div>
                    `;

                    const deleteButton = document.createElement('button');
                    deleteButton.className = 'btn btn-xs btn-error btn-soft mt-4 mx-auto block transition-colors duration-300';
                    deleteButton.textContent = 'Eliminar';
                    deleteButton.setAttribute('type', 'button');
                    deleteButton.addEventListener('click', () => removePreviewFile(fileId, index));

                    fileContainer.appendChild(pdf);
                    fileContainer.appendChild(fileInfo);
                    fileContainer.appendChild(deleteButton);

                    previewContainer.appendChild(fileContainer);
                };

                reader.readAsDataURL(file);

                dataTransfer.items.add(file);
            });

            document.getElementById('files').files = dataTransfer.files;
        }

        function removePreviewFile(fileId, index) {
            const container = document.getElementById(`container-${fileId}`);
            if (container) {
                container.remove();
            }

            selectedFiles.splice(index, 1);

            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            document.getElementById('files').files = dataTransfer.files;

            if (selectedFiles.length === 0 && document.querySelector('#preview-pdf')) {
                document.querySelector('#preview-pdf').remove();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const input_file = document.getElementById('files');
            input_file.addEventListener('change', displayFiles);
        });
    </script>
@endpush 