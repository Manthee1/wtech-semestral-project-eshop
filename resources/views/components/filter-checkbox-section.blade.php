@props(['items', 'name', 'isEnum' => false, 'label' => null, 'expanded' => false])

@php
    $label = $label ?? $name;

@endphp

<div id="{{ $name }}-checkbox-section" class="checkbox-section {{ $expanded ? 'expanded' : '' }} {{ count(request()->input($name, [])) > 0 ? 'has-selected' : '' }}">
    <a href="javascript:void(0)" class="checkbox-section-header flex flex-center py-3" onclick="this.parentElement.classList.toggle('expanded')">
        <h6 class="checkbox-section-name flex-auto m-0">{{ $label }} {{ count(request()->input($name, [])) > 0 ? ' (' . count(request()->input($name, [])) . ')' : '' }}</h6>
        {{-- expand arrow --}}
        <ion-icon name="chevron-down-outline"></ion-icon>
    </a>
    <div class="checkbox-section-content">
        <input type="text" placeholder="Search {{ $name }}..." class="search-input input-small mb-3">

        <div class="flex flex-column flex-nowrap checkbox-container">
            @foreach ($items as $item)
                @php
                    $id = $isEnum ? $item->name : $item->id;
                    $isChecked = in_array($id, request()->input($name, []));
                @endphp
                <div class="flex flex-row flex-left py-2 checkbox-item {{ $item->count == 0 && !$isChecked ? 'inactive' : '' }}">
                    <input type="checkbox" id="{{ $name }}-{{ $id }}" name="{{ $name }}[]" value="{{ $id }}" {{ $isChecked ? 'checked' : '' }} data-name="{{ $item->name }}">
                    <label for="{{ $name }}-{{ $id }}">{{ $item->name }} <small>({{ $item->count }})</small></label>
                </div>
            @endforeach
        </div>
        {{-- Show more and show less buttons --}}
        <div class="flex flex-row flex-center gap-4 show-more-container">
            <a class="button button-small button-clear show-more" id="{{ $name }}-show-more">Show More</a>
            <a class="button button-small button-clear show-less" id="{{ $name }}-show-less">Show Less</a>
        </div>
    </div>
</div>
