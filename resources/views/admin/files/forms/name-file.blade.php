<form action="{{ route('admin.file.name') }}" method="post">
    @csrf
    @method('PUT')
    <input type="hidden" name="product" id="product" value="{{ $product->id }}">
    <input type="hidden" name="client" id="client" value="{{ $product->client->id }}">
    <div class="flex flex-wrap justify-items-center items-center gap-5 mt-5">
        @foreach($files as $file)
            <div class="flex-1 border rounded shadow border-gray-700 min-w-3xs">
                <object class="aspect-square w-full mb-3.5" data="{{ asset($file->file_url) }}"></object>
                <x-forms.floating-input
                        type="text"
                        name="file_names[]"
                        id="{{ $file->id }}_file"
                        label="{{ $file->file_name ?? $file->original_file_name ?? 'Archivo' }}"
                        placeholder="Ingrese el nombre visible para este archivo"
                        error="{{ $errors->has('file_names') ? $errors->first('file_names') : null }}"
                        value="{{ old('file_names.'.$loop->index, $file->file_name ?? '') }}"
                        required={{ true }}
                />
                <input
                        type="hidden"
                        name="original_names[]"
                        value="{{ $file->original_file_name }}"
                        id="{{ $file->id }}_name"
                >
                <input
                        type="hidden"
                        name="files_ids[]"
                        value="{{ $file->id }}"
                        id="{{ $file->id }}_id"
                >
            </div>
        @endforeach
    </div>
    <div class="w-fit mx-auto mt-10">
        <x-forms.submit-button btn_color="btn-success">
            Actualizar nombres
        </x-forms.submit-button>
    </div>
</form>
