<x-layouts.admin
	icon="fa fa-user-tie"
	title="Profil Pimpinan"
	desc="Profil Pimpinan Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Profil Pimpinan', '#']]">

	<form action="{{ route('dashboard.profil-pimpinan') }}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="row">
			<div class="col-md-7 order-2 order-md-1">
				<div class="tile">
					<h3 class="tile-title">Form Profil Pimpinan</h3>
					<div class="tile-body">
						@csrf
						<div>
							<x-admin-textinput.ckeditor
								label="Isi Lengkap Biodata Pimpinan"
								key="konten"
								:value="$data->konten" />
						</div>
					</div>
					<div class="tile-footer">
						<button class="btn btn-primary" type="submit">Simpan</button>&nbsp;&nbsp;&nbsp;
						<button class="btn btn-secondary" type="reset">Reset</button>
					</div>
				</div>
			</div>

			<div class="col-md-5 order-1 order-md-2">
				<div class="tile">
					<div class="mb-3">
						<x-admin-textinput.input
							label="Nama Pimpinan"
							key="nama"
							:value="$data->nama"
							required />
					</div>
					<div class="mb-3">
						<x-admin-textinput.input
							label="Awal Periode"
							placeholder="Contoh : {{ date('Y') }}"
							key="awal_periode"
							:value="$data->awal_periode"
							type="number"
							required />
					</div>
					<div class="mb-3">
						<x-admin-textinput.input
							label="Akhir Periode"
							placeholder="Contoh : {{ date('Y') + 4 }}"
							key="akhir_periode"
							:value="$data->akhir_periode"
							type="number"
							required />
					</div>
					<div>
						<label class="form-label">Foto Pimpinan</label>
						<div style="columns: 100px;">
							@foreach ($data->foto as $item)
								<div style="position: relative;" class="mb-3">
									<img id="thumbnail-kadis-{{ $item['id'] }}" class="img-fluid" src="{{ asset('storage/' . $item['value']) }}"
										alt="{{ $item['id'] . '.jpg' }}">

									<label for="foto-kadis-{{ $item['id'] }}"
										class="btn btn-sm badge btn-info">
										<i class="fa fa-edit"></i> Ubah
									</label>
									<input accept="image/*" id="foto-kadis-{{ $item['id'] }}" name="foto-kadis-{{ $item['id'] }}"
										type="file" style="display: none;">
								</div>
							@endforeach

							@push('scripts')
								<script>
									document.querySelectorAll('input[type="file"]').forEach(input => {
										input.addEventListener('change', function(e) {
											const file = e.target.files[0];
											if (file) {
												const reader = new FileReader();
												reader.onload = function(evt) {
													// Ambil ID input, lalu ganti ke ID thumbnail
													const inputId = e.target.id;
													const img = document.getElementById('thumbnail-' + inputId.split('foto-')[1]);
													if (img) {
														img.src = evt.target.result;
													}
												};
												reader.readAsDataURL(file);
											}
										});
									});
								</script>
							@endpush
						</div>

						@for ($i = 1; $i <= 3; $i++)
							@error('foto-kadis-' . $i)
								<p class="text-danger mb-2">{{ $message }}</p>
							@enderror
						@endfor
					</div>
				</div>
			</div>
		</div>

	</form>
</x-layouts.admin>
