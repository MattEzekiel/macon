<form id="upload-files" class="lg:w-1/2 mx-auto"
      action="{{ isset($product->file_edition) ? route('admin.file.update') : route('admin.file.store') }}"
      method="post" enctype="multipart/form-data">
    @csrf
    @method(isset($product->file_edition) ? 'PUT' :'POST')
    <x-forms.file-input
            :multiple="true"
            :error="$errors->has('files') ? $errors->first('files') : null"
    />
    <input type="hidden" name="product" id="product" value="{{ $product->id }}">
    <div class="w-fit ms-auto">
        <x-forms.submit-button btn_color="btn-success">
            Subir archivos
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
                    fileContainer.className = 'mb-5 relative';
                    fileContainer.id = `container-${fileId}`;

                    const pdf = document.createElement('object');
                    pdf.className = 'aspect-square w-48 h-48 mb-3.5';
                    pdf.data = e.target.result;
                    pdf.type = 'application/pdf';

                    const fileInfo = document.createElement('div');
                    fileInfo.innerHTML = `
                <p><span class="text-sm">Nombre original:</span> ${file.name}</p>
            `;

                    const deleteButton = document.createElement('button');
                    deleteButton.className = 'btn btn-xs btn-error btn-soft mt-2';
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