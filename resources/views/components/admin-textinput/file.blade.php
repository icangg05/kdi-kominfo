@isset($label)
	<label class="form-label">{{ $label }}</label>
@endisset
<input accept="image/*" type="file" value="{{ $value ?? '' }}" @required(isset($required)) id="{{ $key }}" name="{{ $key }}"
	class="form-control">

@error($key)
	<p class="text-danger">{{ $message }}</p>
@enderror
