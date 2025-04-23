@php use Illuminate\Routing\Route; @endphp
<div class="drawer lg:drawer-open lg:p-0 px-5 py-2.5">
    <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col-reverse lg:gap-0 gap-y-5 lg:flex-col justify-center">
        <x-admin.content>
            {{ $slot }}
        </x-admin.content>
        <label for="my-drawer-2" class="btn drawer-button w-fit ms-auto lg:hidden" aria-label="{{ __('general.open_menu') }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 class="inline-block h-5 w-5 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </label>
    </div>
    <nav class="drawer-side">
        <label for="my-drawer-2" aria-label="{{ __('general.close_menu') }}" class="drawer-overlay" aria-labelledby="{{ __('general.close_menu') }}"></label>
        <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4">
            <li class="leading-8 text-2xl text-neutral-content text-center pb-2.5 mb-2.5 border-b-2">{{ env('APP_NAME') }}</li>
            <li class="mb-2.5">
                <a href="{{ route('admin.dashboard') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.dashboard') ? 'bg-base-content text-neutral' : '' }}">
                    <x-microns-home />
                    {{ __('general.dashboard') }}
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.clients') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.clients') ? 'bg-base-content text-neutral' : '' }}">
                    <x-fas-users />
                    {{ __('general.clients') }}
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.products') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.products') ? 'bg-base-content text-neutral' : '' }}">
                    <x-polaris-product-icon />
                    {{ __('general.products') }}
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.files') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.files') ? 'bg-base-content text-neutral' : '' }}">
                    <x-icons.file-icon class="w-4 h-4"/>
                    {{ __('general.files') }}
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.qrs') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.qrs') ? 'bg-base-content text-neutral' : '' }}">
                    <x-vaadin-qrcode />
                    {{ __('general.qrs') }}
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.users') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.users') ? 'bg-base-content text-neutral' : '' }}">
                    <x-fas-user />
                    {{ __('general.users') }}
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.contactos') }}"
                   class="[&>svg]:w-4 {{ request()->routeIs('admin.contactos') ? 'bg-base-content text-neutral' : '' }}">
                    <x-uiw-mail />
                    {{ __('general.contact') }}
                </a>
            </li>
            <li class="mb-2.5">
                <a href="{{ route('admin.logout') }}"
                   class="[&>svg]:w-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                        <path d="M9 12h12l-3 -3" />
                        <path d="M18 15l3 -3" />
                    </svg>
                    {{ __('general.logout') }}
                </a>
            </li>
        </ul>
    </nav>
</div>