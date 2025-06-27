@isset($label)
	<label class="form-label">{{ $label }}</label>
	<span class="{{ isset($required) ? 'text-danger' : 'text-dark' }}">*</span>
@endisset
<input type="{{ $type ?? 'text' }}" value="{{ $value ?? '' }}" @required(isset($required)) id="{{ $key }}"
	name="{{ $key }}"
	class="form-control" placeholder="{{ $placeholder ?? '' }}">

<p class="text-danger err-message" id="err-{{ $key }}"></p>

@error($key)
	<p style="margin-top: -14px" class="text-danger" id="err-{{ $key }}">{{ $message }}</p>
@enderror
