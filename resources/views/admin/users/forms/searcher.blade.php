@php
    use App\Models\User;
    $users_filters = new User()->searcher(request()->all());
@endphp
@if(count($users_filters) > 0)
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
                    <form action="{{ route('admin.users') }}" method="get" class="flex flex-col gap-2.5 mt-1.5">
                        @foreach($users_filters as $key => $value)
                            @if($key === 'client')
                                <div class="w-full">
                                    <x-forms.floating-select
                                            name="{{ $key }}"
                                            id="{{ $key }}"
                                            label="{{ __('users.client') }}"
                                            error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                            value="{{ old($key, request()->get($key)) }}"
                                            :required="false"
                                            :options="$value['data']"
                                    />
                                </div>
                            @elseif($value['type'] === 'suggestion')
                                <div class="w-full">
                                    <x-forms.floating-input-suggestions
                                            type="text"
                                            name="{{ $key }}"
                                            id="{{ $key }}"
                                            label="{{ __('users.' . $key) }}"
                                            error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                            value="{{ old($key, request()->get($key)) }}"
                                            placeholder="{{ __('users.' . $key) }}"
                                            list_id="files_{{ $key }}"
                                            :required="false"
                                            :list="$value['data']"
                                    />
                                </div>
                            @else
                                <div class="w-full">
                                    @php
                                        $formated_data = $value['data']->map(fn($option) => json_decode(json_encode([
                                            'id' => $option['id'],
                                            'value' => $option['value']
                                        ])));
                                    @endphp
                                    <x-forms.floating-select
                                            name="{{ $key }}"
                                            id="{{ $key }}"
                                            label="{{ __('files.deleted') }}"
                                            error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                            value="{{ old($key, request()->get($key)) }}"
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
                            <a class="btn btn-error btn-outline" href="{{ route('admin.users') }}">
                                {{ __('general.reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Filtros para pc -->
        <form action="{{ route('admin.users') }}" method="get" class="hidden lg:flex flex-wrap gap-2.5 items-center mt-1.5">
            @foreach($users_filters as $key => $value)
                @if($key === 'client')
                    <div class="flex-1">
                        <x-forms.floating-select
                                name="{{ $key }}"
                                id="{{ $key }}"
                                label="{{ __('users.client') }}"
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
                                label="{{ __('users.' . $key) }}"
                                error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                value="{{ old($key, request()->get($key)) }}"
                                placeholder="{{ __('users.' . $key) }}"
                                list_id="files_{{ $key }}"
                                :required="false"
                                :list="$value['data']"
                        />
                    </div>
                @else
                    <div class="flex-1">
                        @php
                            $formated_data = $value['data']->map(fn($option) => json_decode(json_encode([
                                'id' => $option['id'],
                                'value' => $option['value']
                            ])));
                        @endphp
                        <x-forms.floating-select
                                name="{{ $key }}"
                                id="{{ $key }}"
                                label="{{ __('files.deleted') }}"
                                error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                value="{{ old($key, request()->get($key)) }}"
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
                <a class="btn btn-error btn-outline" href="{{ route('admin.users') }}">
                    {{ __('general.reset') }}
                </a>
            </div>
        </form>
    </search>
@endif 