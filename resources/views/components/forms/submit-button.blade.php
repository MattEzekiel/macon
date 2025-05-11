@props(['btn_color' => 'btn-neutral', 'class' => '', 'value' => null, 'name' => null])

<button
        type="submit"
        {{ $attributes->merge(['class' => "btn $btn_color $class relative submit-btn w-full md:w-fit"]) }}
        @if(!is_null($name))
            name="{{ $name }}"
        @endif
        @if(!is_null($value))
            value="{{ $value }}"
        @endif
>
    <span class="btn-text whitespace-nowrap">{{ $slot }}</span>

    <!-- Loader -->
    <span class="btn-loader absolute inset-0 hidden items-center justify-center">
        <span class="loading loading-spinner loading-md"></span>
    </span>
</button>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let lastClickedSubmitBtn = null;

        document.querySelectorAll('form .submit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                lastClickedSubmitBtn = this;
            });
        });

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                // Usa el botón clickeado
                const submitBtn = lastClickedSubmitBtn && form.contains(lastClickedSubmitBtn)
                    ? lastClickedSubmitBtn
                    : form.querySelector('.submit-btn');
                if (!submitBtn) return;

                const btnWidth = submitBtn.offsetWidth;
                submitBtn.style.width = btnWidth + 'px';
                submitBtn.disabled = true;

                // Loader
                submitBtn.querySelector('.btn-text').style.visibility = 'hidden';
                const loader = submitBtn.querySelector('.btn-loader');
                loader.classList.remove('hidden');
                loader.style.display = 'flex';

                // Deshabilitar inputs
                form.querySelectorAll('input, textarea, select').forEach(input => {
                    input.classList.add('pointer-events-none', 'opacity-60');
                    input.readOnly = true;

                    const parentContainer = input.closest('.select, .input-container');
                    if (parentContainer) {
                        parentContainer.classList.add('pointer-events-none', 'opacity-60');
                    }
                });

                lastClickedSubmitBtn = null;
            });
        });
    });
</script>
@endpush

