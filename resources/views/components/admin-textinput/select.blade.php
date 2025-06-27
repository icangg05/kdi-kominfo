@isset($label)
	<label class="form-label">{{ $label }}</label>
	<span class="{{ isset($required) ? 'text-danger' : 'text-dark' }}">*</span>
@endisset

<select name="{{ $key }}" id="{{ $key }}" class="form-control select-choices">
	<option value="">Pilih...</option>
	@foreach (App\Models\Jabatan::whereNot('nama', 'Kepala Dinas')->pluck('nama', 'id') as $id => $nama)
		<option value="{{ $id }}">{{ $nama }}</option>
	@endforeach
</select>

<p class="text-danger err-message" id="err-{{ $key }}"></p>

{{-- @push('scripts')
	<script>
		const example = new Choices('#{{ $key }}');
	</script>
@endpush --}}
