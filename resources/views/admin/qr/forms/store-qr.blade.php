<form class="lg:w-1/2 mx-auto" action="{{ route('admin.qr.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <input type="hidden" name="product" id="product" value="{{ $product->id }}">
    <div class="w-fit mx-auto mt-10">
        <x-forms.submit-button btn_color="btn-success">
            Generar QR
        </x-forms.submit-button>
    </div>
</form>