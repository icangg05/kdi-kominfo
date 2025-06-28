@isset($label)
	<label class="form-label">{{ $label }}</label>
	<span class="{{ isset($required) ? 'text-danger' : 'text-dark' }}">*</span>
@endisset
<textarea @required(isset($required)) id="{{ $key }}" name="{{ $key }}" class="form-control" rows="4" placeholder="{{ $placeholder ?? '' }}">{{ old($key) ?? '' }}</textarea>

@error($key)
	<p class="text-danger">{{ $message }}</p>		
@enderror
