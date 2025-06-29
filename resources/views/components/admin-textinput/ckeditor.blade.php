@isset($label)
	<label class="form-label">{{ $label }}</label>
	<span class="text-danger">*</span>
@endisset
<div>
	<textarea name="{{ $key }}" id="{{ $key }}">{{ old($key, $value ?? '') ?? '' }}</textarea>
	<p class="text-danger err-message" id="err-{{ $key }}"></p>

	@error($key)
		<p style="margin-top: -14px" class="text-danger err-message" id="err-{{ $key }}">{{ $message }}</p>
	@enderror

	@push('scripts')
		<script>
			let editor;
			ClassicEditor
				.create(document.querySelector('#{{ $key }}'))
				.then(newEditor => {
					editor = newEditor;
					window.editor = newEditor; // simpan ke global agar bisa diakses saat klik <tr>
				})
				.catch(error => {
					console.error(error);
				});
		</script>
	@endpush
</div>
