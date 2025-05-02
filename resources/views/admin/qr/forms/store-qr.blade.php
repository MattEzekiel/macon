<form class="lg:w-1/2 mx-auto" action="{{ route('admin.qr.store') }}" method="post">
    @csrf
    @method('POST')
    <input type="hidden" name="product" id="product" value="{{ $product->id }}">
    <input type="hidden" name="client" id="client" value="{{ $product->client->id }}">
    <div class="flex flex-wrap justify-items-center items-center gap-5 mt-5">
        @foreach($files as $file)
            <div class="flex-1 border rounded shadow border-gray-700">
                <div class="relative">
                    <div class="skeleton h-80 w-full file-loader" aria-label="loading..."></div>
                    <object
                            class="aspect-square w-full mb-3.5 max-h-80"
                            data="{{ route('files.get', ['id' => $file->id]) }}"
                            onload="hideLoader(this)"
                            id="file-object-{{$file->id}}"
                    ></object>
                </div>
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
            </div>
        @endforeach
    </div>
    <div class="w-fit mx-auto mt-10">
        <x-forms.submit-button btn_color="btn-success">
            {{ __('qrs.generate_qr') }}
        </x-forms.submit-button>
    </div>
</form>
@push('scripts')
    <script>
        function hideLoader(objectElement) {
            const loaderElement = objectElement.parentElement.querySelector('.skeleton');
            if (loaderElement) {
                loaderElement.remove();
            }
        }
    </script>
@endpush