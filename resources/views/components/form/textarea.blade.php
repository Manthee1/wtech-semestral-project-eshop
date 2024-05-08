{{-- Input prop --}}
@props(['name', 'label', 'value' => null, 'disabled' => false, 'required' => false, 'id' => null, 'class' => ''])

<div class="input-wrapper {{ $class }}">
    <textarea name="{{ $name }}" id="{{ $id ?? $name }}" {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }} placeholder="">{{ old($name, $value) }}</textarea>
    <label for="{{ $id ?? $name }}">{{ $label }} {!! $required ? '<span>*</span>' : '' !!}</label>
</div>
