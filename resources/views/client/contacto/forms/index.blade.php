<form action="{{ route('client.contact.store') }}" method="POST" id="contact-form"
      class="w-full md:w-1/2 mx-auto grid grid-cols-1 md:grid-cols-2 gap-4">
    @csrf
    @method('POST')
    @component("components.forms.floating-input",
        [
            'type' => 'text',
            'name' => 'email',
            'id' => 'email',
            'label' => __('general.email'),
            'placeholder' => __('general.email'),
            'error' => $errors->has("email") ? $errors->first("email") : null,
            'value' => old("email", auth()->user() !== null ? auth()->user()->email : ''),
            'required' => true
        ]
)
    @endcomponent
    @component("components.forms.floating-input",
[
    'type' => 'text',
    'name' => 'subject',
    'id' => 'subject',
    'label' => __('general.subject'),
    'placeholder' => __('general.subject'),
    'error' => $errors->has("subject") ? $errors->first("subject") : null,
    'value' => old("subject", ''),
    'required' => true
])@endcomponent
    <div class="col-span-1 md:col-span-2 min-h-[80px]">
        @component("components.forms.floating-textarea",
    [
        'name' => 'message',
        'id' => 'message',
        'label' => __('general.message'),
        'placeholder' => __('general.message'),
        'error' => $errors->has("message") ? $errors->first("message") : null,
        'value' => old("message", ''),
        'required' => true
    ])@endcomponent
    </div>
    <div class="col-span-1 md:col-span-2 flex flex-col md:flex-row md:justify-end items-center">
        <x-forms.submit-button btn_color="btn-success">
            {{ __('general.send') }}
        </x-forms.submit-button>
    </div>
</form>