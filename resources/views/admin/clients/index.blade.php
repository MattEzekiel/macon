@extends('layouts.admin')

@section('admin')
    <main>
        <h1 class="text-2xl leading-8 mb-5">
            Clients
        </h1>
        <button class="btn" onclick="my_modal_1.showModal()">Añadir nuevo cliente</button>
        <dialog id="my_modal_1" class="modal">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Crear un nuevo cliente</h3>
                <small class="py-2 text-xs">Presione ESC para cerrar</small>
                <div class="modal-action mt-2.5">
                    <div class="w-full">
                        <form id="create-user" action="{{ route('admin.client.store') }}" method="post"
                              class="w-full grid lg:grid-cols-2 gap-2.5">
                            <p class="mb-5 mt-3 col-span-2">Complete los siguientes datos:</p>
                            @csrf
                            <x-forms.floating-input
                                    type="text"
                                    name="razon-social"
                                    id="razon-social"
                                    label="Razón social"
                                    placeholder="Razón social"
                                    required={{ true }}
                            />
                            <x-forms.floating-input
                                    type="text"
                                    name="cuit"
                                    id="cuit"
                                    label="Cuit"
                                    placeholder="CUIT"
                                    required={{ true }}
                            />
                            <x-forms.floating-input
                                    type="text"
                                    name="nombre"
                                    id="nombre"
                                    label="Nombre"
                                    placeholder="Nombre"
                                    required={{ true }}
                            />
                        </form>
                        <div class="flex flex-wrap lg:justify-end items-center mt-2.5 gap-2.5">
                            <form method="dialog">
                                <button class="btn">Cerrar</button>
                            </form>
                            <button type="submit" form="create-user" class="btn btn-soft btn-success">Crear usuario
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </dialog>
    </main>
@endsection
