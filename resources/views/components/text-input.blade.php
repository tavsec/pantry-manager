@props([
    'type' => 'text',
    'name',
    'value' => '',
    'label' => null,
    'required' => false,
    'placeholder' => '',
])

@if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-600">*</span>
        @endif
    </label>
@endif

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ old($name, $value) }}"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($required) required @endif
    {{ $attributes->merge(['class' => 'mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ' . ($errors->has($name) ? 'border-red-300' : '')]) }}
>

@error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
