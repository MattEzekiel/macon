@extends('layouts.general')
@section('general')
    <main class="grid place-items-center h-screen">
        @if(count($files) > 0)
            <ul>
                @foreach($files as $file)
                    <li>
                        <a href="{{ asset($file->file_url) }}" target="_blank">{{ $file->file_name }}</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-2xl text-center">No hay archivos</p>
        @endif
    </main>
@endsection
