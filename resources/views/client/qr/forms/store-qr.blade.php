<form class="w-full px-4 mx-auto" action="{{ route('client.qr.store') }}" method="post">
    @csrf
    @method('POST')
    <input type="hidden" name="product" id="product" value="{{ $product->id }}">
    <input type="hidden" name="client" id="client" value="{{ auth()->user()->client_id }}">
    <div class="flex flex-wrap justify-items-center items-center gap-5 mt-5">
        @foreach($files as $file)
            <div class="w-80 border rounded shadow border-gray-700 p-4 transition-all duration-300 hover:shadow-xl group">
                <div class="relative w-full aspect-square mb-3.5">
                     <x-skeleton class="absolute inset-0 h-full w-full" />
                     @php
                         $encryptedId = Crypt::encrypt($file->id);
                     @endphp
                    <iframe
                        class="absolute inset-0 w-full h-full hidden"
                        src="{{ route('files.get', ['id' => $encryptedId]) }}#toolbar=0"
                        onload="this.classList.remove('hidden'); this.previousElementSibling.classList.add('hidden');"
                        id="file-iframe-{{$file->id}}"
                        frameborder="0"
                    ></iframe>
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
    <div class="w-full lg:w-fit mx-auto mt-10">
        <x-forms.submit-button btn_color="btn-success">
            {{ __('qrs.generate_qr') }}
        </x-forms.submit-button>
    </div>
</form>
@push('scripts')
    <script>
        // La función hideLoader ya no es necesaria
    </script>
@endpush