@isset($label)
	<label class="form-label">{{ $label }}</label>
	<span class="{{ isset($required) ? 'text-danger' : 'text-dark' }}">*</span>
@endisset

<input type="file" value="{{ $value ?? '' }}" @required(isset($required) && $required == true)  id="{{ $key }}" name="{{ $key }}"
	class="form-control">

@error($key)
	<p class="text-danger err-message">{{ $message }}</p>
@enderror
