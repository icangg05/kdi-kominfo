@isset($label)
	<label class="form-label">{{ $label }}</label>
@endisset
<textarea @required(isset($required)) id="{{ $key }}" name="{{ $key }}" class="form-control" rows="4" placeholder="{{ $placeholder ?? '' }}">{{ $value ?? '' }}</textarea>

@error($key)
<p class="text-danger">{{ $message }}</p>		
@enderror
