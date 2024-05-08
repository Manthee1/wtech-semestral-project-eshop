{{-- Input prop --}}
@props(['type' => 'text', 'name', 'label', 'value' => null, 'disabled' => false, 'required' => false, 'min' => null, 'max' => null, 'step' => null, 'maxlength' => null, 'id' => null, 'class' => '', 'checked' => false])

<div class="input-wrapper {{ $class }}">
    @if ($type === 'radio-tab')
        <input type="radio" class="hidden" name="{{ $name }}" id="{{ $id ?? $name }}" value="{{ $value }}" {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }} {{ $checked ? 'checked' : '' }}>
        <label for="{{ $id ?? $name }}" class="tab-button">{{ $label }}</label>
    @elseif ($type === 'checkbox' || $type === 'radio')
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}" value="{{ $value }}" {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }} {{ $checked ? 'checked' : '' }}>
        <label for="{{ $id ?? $name }}">{{ $label }} {!! $required ? '<span>*</span>' : '' !!}</label>
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}" value="{{ old($name, $value) }}" {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }} {{ $min ? 'min=' . $min : '' }}
            {{ $max ? 'max=' . $max : '' }} {{ $step ? 'step=' . $step : '' }} {{ $maxlength ? 'maxlength=' . $maxlength : '' }} placeholder="">
        <label for="{{ $id ?? $name }}">{{ $label }} {!! $required ? '<span>*</span>' : '' !!}</label>
    @endif
</div>


<script></script>
