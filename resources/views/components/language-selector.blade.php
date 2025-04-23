<form action="{{ route('admin.change.language') }}" method="POST" class="flex items-center space-x-2">
    @csrf
    <select name="language" onchange="this.form.submit()" class="select select-bordered w-full max-w-xs">
        <option value="es" {{ app()->getLocale() === 'es' ? 'selected' : '' }}>Español</option>
        <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
    </select>
</form> 