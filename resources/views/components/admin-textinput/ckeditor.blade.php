@isset($label)
	<label class="form-label">{{ $label }}</label>
@endisset
<div>
	<textarea name="{{ $key }}" id="{{ $key }}">{{ $value }}</textarea>
	@push('scripts')
		<script>
			ClassicEditor.create(document.querySelector('#{{ $key }}')).catch(error => console.error(error));
		</script>
	@endpush
</div>
