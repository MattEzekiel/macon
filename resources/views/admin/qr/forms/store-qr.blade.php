<form class="lg:w-1/2 mx-auto" action="{{ route('admin.qr.store') }}" method="post">
    @csrf
    @method('POST')
    <input type="hidden" name="product" id="product" value="{{ $product->id }}">
    <input type="hidden" name="client" id="client" value="{{ $product->client->id }}">
    @if(!is_null($url))
        <input type="hidden" name="url" id="url" value="{{ $url }}">
    @endif
    <div class="grid place-items-center">
        {!! $qr_code !!}
    </div>
    <div class="w-fit mx-auto mt-10">
        <x-forms.submit-button btn_color="btn-success">
            Generar QR
        </x-forms.submit-button>
    </div>
</form>