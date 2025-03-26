@props([
'steps' => [],
'current' => 0
])
@if(count($steps) > 0)
    <ul class="steps steps-vertical lg:steps-horizontal mx-auto w-full">
        @foreach($steps as $step => $label)
            <li @if($step < $current) data-content="✓"
                @endif class="step {{ $step <= $current ? 'step-primary' : '' }}">{{ $label }}</li>
        @endforeach
    </ul>
@endif