{{-- Input prop --}}
@props(['name', 'label', 'value' => null, 'disabled' => false, 'required' => false, 'id' => null, 'class' => '', 'options' => []])

<div class="input-wrapper {{ $class }}">
    <select name="{{ $name }}" id="{{ $id ?? $name }}" {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }}>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ $value == $optionValue ? 'selected' : '' }}>{{ $optionLabel }}</option>
        @endforeach
    </select>
    <label for="{{ $id ?? $name }}">{{ $label }} {!! $required ? '<span>*</span>' : '' !!}</label>
</div>
