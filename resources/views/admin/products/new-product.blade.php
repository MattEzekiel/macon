@extends('layouts.admin')

@section('admin')
    <x-heading1>
        Nuevo producto
    </x-heading1>
    <div class="mt-3.5">
        @include('admin.products.forms.store-product')
    </div>
@endsection