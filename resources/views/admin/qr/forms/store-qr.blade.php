<form class="w-full lg:w-1/2 mx-auto" action="{{ route('admin.qr.store') }}" method="post">
    @csrf
    @method('POST')
    <input type="hidden" name="product" id="product" value="{{ $product->id }}">
    <input type="hidden" name="client" id="client" value="{{ $product->client->id }}">
    <div class="flex flex-col lg:flex-row flex-wrap justify-items-center items-center gap-5 mt-5">
        @foreach($files as $file)
            <div class="w-full lg:flex-1 border rounded shadow border-gray-700">
                <object class="aspect-square w-full mb-3.5" data="{{ asset($file->file_url) }}"></object>
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
    <div class="w-full lg:w-fit mx-auto mt-10">
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