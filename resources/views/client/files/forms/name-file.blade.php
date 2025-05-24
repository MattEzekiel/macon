<form action="{{ route('client.file.name') }}" method="post">
    @csrf
    @method('PUT')
    <input type="hidden" name="product" id="product" value="{{ $product->id }}">
    <div class="flex flex-wrap justify-items-center items-center gap-5 mt-5">
        @foreach($files as $file)
            <div class="flex-1 border rounded shadow border-gray-700 min-w-3xs">
                <object class="aspect-square w-full mb-3.5 max-h-80"
                        data="{{ route('files.get', ['id' => $file->id]) }}"></object>
                <x-forms.floating-input
                        type="text"
                        name="file_names[]"
                        id="{{ $file->id }}_file"
                        label="{{ $file->file_name ?? $file->original_file_name ?? __('general.file') }}"
                        placeholder="{{ __('qrs.insert_file_name') }}"
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
            {{ __('files.update_names') }}
        </x-forms.submit-button>
    </div>
</form> 