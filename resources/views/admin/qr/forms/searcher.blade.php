@php
    use App\Models\QRs;
    $fields = new QRs()->searcher();
@endphp
@if(count($fields) > 0)
    <search>
        <form action="{{ route('admin.qrs') }}" method="get" class="flex flex-wrap gap-2.5 items-center mt-1.5">
            @foreach($fields as $key => $value)
                @if($value['type'] === 'select')
                    <div class="flex-1">
                        <x-forms.floating-select
                                name="{{ $key }}"
                                id="{{ $key }}"
                                label="{{ __('qrs.' . $key) }}"
                                error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                value="{{ old($key, request()->get($key)) }}"
                                :required="false"
                                :options="$value['data']"
                        />
                    </div>
                @elseif($value['type'] === 'suggestion')
                    <div class="flex-1">
                        <x-forms.floating-input-suggestions
                                type="text"
                                name="{{ $key }}"
                                id="{{ $key }}"
                                label="{{ __('qrs.' . $key) }}"
                                error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                value="{{ old($key, request()->get($key)) }}"
                                placeholder="{{ __('qrs.' . $key) }}"
                                list_id="products_name"
                                :required="false"
                                :list="$value['data']"
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
                <a class="btn btn-error btn-outline" href="{{ route('admin.qrs') }}">
                    {{ __('general.reset') }}
                </a>
            </div>
        </form>
    </search>
@endif