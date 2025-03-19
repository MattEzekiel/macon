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
            <li><a>Sidebar Item 1</a></li>
            <li><a>Sidebar Item 2</a></li>
        </ul>
    </nav>
</div>