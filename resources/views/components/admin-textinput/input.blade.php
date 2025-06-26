@isset($label)
	<label class="form-label">{{ $label }}</label>
@endisset
<input value="{{ $value ?? '' }}" @required(isset($required)) id="{{ $key }}" name="{{ $key }}"
	class="form-control" placeholder="{{ $placeholder ?? '' }}">

@error($key)
	<p class="text-danger">{{ $message }}</p>
@enderror
