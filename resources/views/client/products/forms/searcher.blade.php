@php
    use App\Models\Products;
    $fields = new Products()->searcher(request()->all(), true);
@endphp
@if(count($fields) > 0)
    <search>
        <!-- Filtros para mobile -->
        <div class="lg:hidden mb-4">
            <div class="collapse bg-base-200">
                <input type="checkbox" /> 
                <div class="collapse-title text-md font-medium flex items-center gap-2">
                    <x-zondicon-filter class="w-3 h-3" />
                    {{ __('general.filters') }}
                </div>
                <div class="collapse-content">
                    <form action="{{ route('client.products') }}" method="get" class="flex flex-col gap-2.5 mt-1.5">
                        @foreach($fields as $key => $value)
                            @if($value['type'] === 'suggestion')
                                <div class="w-full">
                                    <x-forms.floating-input-suggestions
                                            type="text"
                                            name="{{ $key }}"
                                            id="{{ $key }}"
                                            label="{{ __('products.' . $key) }}"
                                            error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                            value="{{ request()->get($key) }}"
                                            placeholder="{{ __('products.' . $key) }}"
                                            list_id="products_name"
                                            :required="false"
                                            :list="$value['data']"
                                    />
                                </div>
                            @else
                                <div class="w-full">
                                    @php
                                        $formated_data = $value['data']->map(fn($option) => json_decode(json_encode(['id' => $option['id'],
                                            'value' => $option['value']])));
                                    @endphp
                                    <x-forms.floating-select
                                            name="{{ $key }}"
                                            id="{{ $key }}"
                                            label="{{ __('products.' . $key) }}"
                                            error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                            value="{{ request()->get($key) }}"
                                            :required="false"
                                            :options="$formated_data"
                                    />
                                </div>
                            @endif
                        @endforeach
                        <div class="flex gap-2.5">
                            <x-forms.submit-button class="max-md:w-fit">
                                {{ __('general.search') }}
                            </x-forms.submit-button>
                            <a class="btn btn-error btn-outline" href="{{ route('client.products') }}">
                                {{ __('general.reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Filtros para pc -->
        <form action="{{ route('client.products') }}" method="get" class="hidden lg:flex flex-wrap gap-2.5 items-center mt-1.5">
            @foreach($fields as $key => $value)
                @if($value['type'] === 'suggestion')
                    <div class="flex-1">
                        <x-forms.floating-input-suggestions
                                type="text"
                                name="{{ $key }}"
                                id="{{ $key }}"
                                label="{{ __('products.' . $key) }}"
                                error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                value="{{ request()->get($key) }}"
                                placeholder="{{ __('products.' . $key) }}"
                                list_id="products_name"
                                :required="false"
                                :list="$value['data']"
                        />
                    </div>
                @else
                    <div class="flex-1">
                        @php
                            $formated_data = $value['data']->map(fn($option) => json_decode(json_encode(['id' => $option['id'],
                                'value' => $option['value']])));
                        @endphp
                        <x-forms.floating-select
                                name="{{ $key }}"
                                id="{{ $key }}"
                                label="{{ __('products.' . $key) }}"
                                error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                value="{{ request()->get($key) }}"
                                :required="false"
                                :options="$formated_data"
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
                <a class="btn btn-error btn-outline" href="{{ route('client.products') }}">
                    {{ __('general.reset') }}
                </a>
            </div>
        </form>
    </search>
@endif 