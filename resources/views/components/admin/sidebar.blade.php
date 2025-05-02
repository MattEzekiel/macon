@php use Illuminate\Routing\Route; @endphp
<div class="drawer lg:drawer-open">
    <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col items-center justify-start px-0 sm:px-5 py-2.5">
        <label for="my-drawer-2" class="btn drawer-button w-fit ms-auto lg:hidden fixed top-4 right-4 z-50" aria-label="{{ __('general.open_menu') }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 class="inline-block h-5 w-5 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </label>
        <x-admin.content>
            {{ $slot }}
        </x-admin.content>
    </div>
    <nav class="drawer-side z-[100]">
        <label for="my-drawer-2" aria-label="{{ __('general.close_menu') }}" class="drawer-overlay" aria-labelledby="{{ __('general.close_menu') }}"></label>
        <ul class="menu bg-base-200 text-base-content min-h-full w-64 sm:w-80 p-2 sm:p-4 flex flex-col relative">
            <label for="my-drawer-2" class="btn btn-sm btn-circle absolute right-2 top-2 lg:hidden cursor-pointer z-[100]" aria-label="{{ __('general.close_menu') }}">
                <x-icons.close-icon />
            </label>
            <li class="leading-8 text-xl sm:text-2xl text-neutral-content text-center pb-2.5 mb-2.5 border-b-2">{{ env('APP_NAME') }}</li>
            <div class="flex-grow">
                <li class="mb-2.5">
                    <a href="{{ route('admin.dashboard') }}"
                       class="[&>svg]:w-4 max-sm:text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-base-content text-neutral' : '' }}">
                        <x-microns-home />
                        {{ __('general.dashboard') }}
                    </a>
                </li>
                <li class="mb-2.5">
                    <a href="{{ route('admin.clients') }}"
                       class="[&>svg]:w-4 max-sm:text-sm {{ request()->routeIs('admin.clients') ? 'bg-base-content text-neutral' : '' }}">
                        <x-fas-users />
                        {{ __('general.clients') }}
                    </a>
                </li>
                <li class="mb-2.5">
                    <a href="{{ route('admin.products') }}"
                       class="[&>svg]:w-4 max-sm:text-sm {{ request()->routeIs('admin.products') ? 'bg-base-content text-neutral' : '' }}">
                        <x-polaris-product-icon />
                        {{ __('general.products') }}
                    </a>
                </li>
                <li class="mb-2.5">
                    <a href="{{ route('admin.files') }}"
                       class="[&>svg]:w-4 max-sm:text-sm {{ request()->routeIs('admin.files') ? 'bg-base-content text-neutral' : '' }}">
                        <x-icons.file-icon class="w-4 h-4"/>
                        {{ __('general.files') }}
                    </a>
                </li>
                <li class="mb-2.5">
                    <a href="{{ route('admin.qrs') }}"
                       class="[&>svg]:w-4 max-sm:text-sm {{ request()->routeIs('admin.qrs') ? 'bg-base-content text-neutral' : '' }}">
                        <x-vaadin-qrcode />
                        {{ __('general.qrs') }}
                    </a>
                </li>
                <li class="mb-2.5">
                    <a href="{{ route('admin.users') }}"
                       class="[&>svg]:w-4 max-sm:text-sm {{ request()->routeIs('admin.users') ? 'bg-base-content text-neutral' : '' }}">
                        <x-fas-user />
                        {{ __('general.users') }}
                    </a>
                </li>
                <li class="mb-2.5">
                    <a href="{{ route('admin.contactos') }}"
                       class="[&>svg]:w-4 max-sm:text-sm {{ request()->routeIs('admin.contactos') ? 'bg-base-content text-neutral' : '' }}">
                        <x-uiw-mail />
                        {{ __('general.contact') }}
                    </a>
                </li>
            </div>
            <div class="mt-auto border-t-2 pt-2.5">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="w-full sm:w-1/2 flex justify-center">
                        <x-language-selector />
                    </div>
                    <a href="{{ route('admin.logout') }}"
                       class="btn btn-ghost btn-sm [&>svg]:w-4 w-full sm:w-auto text-center justify-center sm:text-sm">
                       <x-tabler-logout />
                        {{ __('general.logout') }}
                    </a>
                </div>
            </li>
        </ul>
    </nav>
</div>
