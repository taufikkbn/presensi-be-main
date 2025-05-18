<div class="form-group mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $isReadOnly ? 'readonly' : '' }}
        {{ $attributes->merge(['class' => 'form-control']) }}
    >
</div>
