@props([
    'multiple' => false,
    'error' => null,
    'disabled' => false,
])
<div class="w-full">
    <fieldset class="fieldset">
        <legend class="fieldset-legend">{{ __('general.upload') }} {{ $multiple ? __('general.files') : __('general.file') }}</legend>
        <input
                type="file"
                name="{{ $multiple ? 'files[]' : 'files' }}"
                id="files"
                class="w-full file-input {{ !is_null($error) ? 'file-input-error' : '' }}"
                required
                @disabled($disabled)
                {{ $multiple ? 'multiple' : '' }}
                accept=".pdf"
        />
        <label class="fieldset-label" for="files">Max 10MB</label>
    </fieldset>
</div>