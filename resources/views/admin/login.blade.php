@extends('layouts.auth')

@section('auth')
    <main>
        <form method="post">
            @csrf
            Formulario
        </form>
    </main>
@endsection
