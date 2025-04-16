@php use Illuminate\Routing\Route; @endphp
<div class="drawer lg:drawer-open lg:p-0 px-5 py-2.5">
    <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col-reverse lg:gap-0 gap-y-5 lg:flex-col justify-center">
        <x-admin.content>
            {{ $slot }}
        </x-admin.content>
        <label for="my-drawer-2" class="btn drawer-button w-fit ms-auto lg:hidden" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 class="inline-block h-5 w-5 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </label>
    </div>
    <nav class="drawer-side">
        <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay" aria-labelledby="Close menu"></label>
        <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4">
            <li class="leading-8 text-2xl text-neutral-content text-center pb-2.5 mb-2.5 border-b-2">{{ env('APP_NAME') }}</li>
            <li class="mb-2.5">
                <a href="{{ route('admin.dashboard') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.dashboard') ? 'bg-base-content text-neutral' : '' }}">
                    <x-microns-home />
                    Dashboard
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.clients') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.clients') ? 'bg-base-content text-neutral' : '' }}">
                    <x-fas-users />
                    Clientes
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.products') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.products') ? 'bg-base-content text-neutral' : '' }}">
                    <x-polaris-product-icon />
                    Productos
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.files') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.files') ? 'bg-base-content text-neutral' : '' }}">
                    <x-icons.file-icon class="w-4 h-4"/>
                    Archivos
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.qrs') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.qrs') ? 'bg-base-content text-neutral' : '' }}">
                    <x-vaadin-qrcode />
                    QR's
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.users') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.users') ? 'bg-base-content text-neutral' : '' }}">
                    <x-fas-user />
                    Users
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.contactos') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.contactos') ? 'bg-base-content text-neutral' : '' }}">
                    <x-uiw-mail />
                    Contacto
                </a>
            </li>
        </ul>
    </nav>
</div>