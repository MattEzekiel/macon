@php
    $isAdmin = request()->routeIs('admin.*');
    $route = $isAdmin ? route('admin.change.language') : route('client.change.language');
@endphp
<form action="{{ $route }}" method="POST" class="w-full">
    @csrf
    <select name="language" onchange="this.form.submit()" class="select select-bordered w-full">
        <option value="es" {{ app()->getLocale() === 'es' ? 'selected' : '' }}>{{ __('general.spanish') }}</option>
        <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>{{ __('general.english') }}</option>
    </select>
</form> 