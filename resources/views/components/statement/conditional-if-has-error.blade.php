@if ($errors->has($error))
    <div class="invalid-feedback">
        {{ $errors->first($error) }}
    </div>
@else
    {{ $slot }}
@endif
