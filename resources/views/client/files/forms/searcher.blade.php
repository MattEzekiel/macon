@php
    use App\Models\Files;
    $fields = new Files()->searcher(request()->all(), true);
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
                    <form action="{{ route('client.files') }}" method="get" class="flex flex-col gap-2.5 mt-1.5">
                        @foreach($fields as $key => $value)
                            @if($key === 'product')
                                <div class="w-full">
                                    @php
                                        $data = $value['data']->map(fn($option) => json_decode(json_encode([
                                            'id' => $option->id,
                                            'value' => $option->name
                                        ])));
                                    @endphp
                                    <x-forms.floating-select
                                            name="{{ $key }}"
                                            id="{{ $key }}"
                                            label="{{ __('files.product') }}"
                                            error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                            value="{{ request()->get($key) }}"
                                            :required="false"
                                            :options="$data"
                                    />
                                </div>
                            @elseif($value['type'] === 'suggestion')
                                <div class="w-full">
                                    <x-forms.floating-input-suggestions
                                            type="text"
                                            name="{{ $key }}"
                                            id="{{ $key }}"
                                            label="{{ $key === 'file_name' ? __('files.file_name') : __('files.original_file_name') }}"
                                            error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                            value="{{ request()->get($key) }}"
                                            placeholder="{{ $key === 'file_name' ? __('files.file_name') : __('files.original_file_name') }}"
                                            list_id="files_{{ $key }}"
                                            :required="false"
                                            :list="$value['data']"
                                    />
                                </div>
                            @endif
                        @endforeach
                        @if(isset($fields['deleted']))
                            <div class="w-full">
                                @php
                                    $formated_data = $fields['deleted']['data']->map(fn($option) => json_decode(json_encode([
                                        'id' => $option['id'],
                                        'value' => $option['value']
                                    ])));
                                @endphp
                                <x-forms.floating-select
                                        name="deleted"
                                        id="deleted"
                                        label="{{ __('files.deleted') }}"
                                        error="{{ $errors->has('deleted') ? $errors->first('deleted') : null }}"
                                        value="{{ request()->get('deleted') }}"
                                        :required="false"
                                        :options="$formated_data"
                                />
                            </div>
                        @endif
                        <div class="flex gap-2.5">
                            <x-forms.submit-button class="max-md:w-fit">
                                {{ __('general.search') }}
                            </x-forms.submit-button>
                            <a class="btn btn-error btn-outline" href="{{ route('client.files') }}">
                                {{ __('general.reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Filtros para pc -->
        <form action="{{ route('client.files') }}" method="get" class="hidden lg:flex flex-wrap gap-2.5 items-center mt-1.5">
            @foreach($fields as $key => $value)
                @if($key === 'product')
                    <div class="flex-1">
                        @php
                            $data = $value['data']->map(fn($option) => json_decode(json_encode([
                                'id' => $option->id,
                                'value' => $option->name
                            ])));
                        @endphp
                        <x-forms.floating-select
                                name="{{ $key }}"
                                id="{{ $key }}"
                                label="{{ __('files.product') }}"
                                error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                value="{{ request()->get($key) }}"
                                :required="false"
                                :options="$data"
                        />
                    </div>
                @elseif($value['type'] === 'suggestion')
                    <div class="flex-1">
                        <x-forms.floating-input-suggestions
                                type="text"
                                name="{{ $key }}"
                                id="{{ $key }}"
                                label="{{ $key === 'file_name' ? __('files.file_name') : __('files.original_file_name') }}"
                                error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                value="{{ request()->get($key) }}"
                                placeholder="{{ $key === 'file_name' ? __('files.file_name') : __('files.original_file_name') }}"
                                list_id="files_{{ $key }}"
                                :required="false"
                                :list="$value['data']"
                        />
                    </div>
                @endif
            @endforeach
            @if(isset($fields['deleted']))
                <div class="flex-1">
                    @php
                        $formated_data = $fields['deleted']['data']->map(fn($option) => json_decode(json_encode([
                            'id' => $option['id'],
                            'value' => $option['value']
                        ])));
                    @endphp
                    <x-forms.floating-select
                            name="deleted"
                            id="deleted"
                            label="{{ __('files.deleted') }}"
                            error="{{ $errors->has('deleted') ? $errors->first('deleted') : null }}"
                            value="{{ request()->get('deleted') }}"
                            :required="false"
                            :options="$formated_data"
                    />
                </div>
            @endif
            <div>
                <x-forms.submit-button>
                    {{ __('general.search') }}
                </x-forms.submit-button>
            </div>
            <div>
                <a class="btn btn-error btn-outline" href="{{ route('client.files') }}">
                    {{ __('general.reset') }}
                </a>
            </div>
        </form>
    </search>
@endif 