@php
    use App\Models\Clients;
    $fields = new Clients()->searcher(request()->all());
@endphp
@if(count($fields) > 0)
    <form action="{{ route('admin.clients') }}" method="get" class="flex flex-wrap gap-2.5 items-center mt-1.5">
        @foreach($fields as $key => $value)
            @if($key === 'client')
                <div class="flex-1">
                    <x-forms.floating-select
                            name="{{ $key }}"
                            id="{{ $key }}"
                            label="{{ __('clients.' . $key) }}"
                            error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                            value="{{ old($key, request()->get($key)) }}"
                            required={{ false }}
                            placeholder="{{ __('clients.' . $key) }}"
                            :options="$value['data']->map(fn($option) => json_decode(json_encode(['id' => $option->id, 'value' => $option->legal_name])))"
                    />
                </div>
            @else
                <div class="flex-1">
                    <x-forms.floating-select
                            name="{{ $key }}"
                            id="{{ $key }}"
                            label="{{ __('clients.' . $key) }}"
                            error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                            value="{{ old($key, request()->get($key)) }}"
                            required={{ false }}
                        placeholder="{{ __('clients.' . $key) }}"
                            :options="$value['data']->map(fn($option) => json_decode(json_encode(['id' => $option['id'], 'value' => $option['value']])))"
                    />
                </div>
            @endif
        @endforeach
        <div>
            <x-forms.submit-button>
                {{ __('general.search') }}
            </x-forms.submit-button>
        </div>
        <div>
            <a class="btn btn-error btn-outline" href="{{ route('admin.clients') }}">
                {{ __('general.reset') }}
            </a>
        </div>
    </form>
@endif