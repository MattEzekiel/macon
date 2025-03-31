<form class="lg:w-1/2 mx-auto"
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