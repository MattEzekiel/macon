@php
    use App\Models\Files;
    $fields = new Files()->searcher(request()->all());
@endphp
@if(count($fields) > 0)
    <search>
        <form action="{{ route('admin.files') }}" method="get" class="flex flex-wrap gap-2.5 items-center mt-1.5">
            @foreach($fields as $key => $value)
                @if($key === 'client' || $key === 'product')
                    <div class="flex-1">
                        @php
                            $data = $value['data']->map(fn($option) => json_decode(json_encode([
                                'id' => $option->id,
                                'value' => $key === 'client' ? $option->legal_name : $option->name
                            ])));
                        @endphp
                        <x-forms.floating-select
                                name="{{ $key }}"
                                id="{{ $key }}"
                                label="{{ $key === 'client' ? __('files.client') : __('files.product') }}"
                                error="{{ $errors->has($key) ? $errors->first($key) : null }}"
                                value="{{ old($key, request()->get($key)) }}"
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
                                value="{{ old($key, request()->get($key)) }}"
                                placeholder="{{ $key === 'file_name' ? __('files.file_name') : __('files.original_file_name') }}"
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
                <a class="btn btn-error btn-outline" href="{{ route('admin.files') }}">
                    {{ __('general.reset') }}
                </a>
            </div>
        </form>
    </search>
@endif 