<x-layouts.admin
	icon="fa fa-file"
	title="Dokumen"
	desc="Dokumen Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Dokumen', '#']]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form id="form" action="{{ route('dashboard.dokumen.store') }}" method="post" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="_method" id="formMethod" value="post">
				<div class="tile">
					<h3 class="tile-title">Form Dokumen</h3>
					<div class="tile-body">
						@csrf
						<div class="mb-3">
							<x-admin-textinput.input
								label="Judul Dokumen"
								placeholder="Tulis judul dokumen..."
								key="judul"
								required />
						</div>
						<div class="mb-3">
							<x-admin-textinput.select
								:data-select="App\Models\KategoriDokumen::pluck('nama', 'id')"
								label="Kategori"
								key="kategori_dokumen_id"
								required />
						</div>
						<div class="mb-3">
							<x-admin-textinput.textarea
								label="Deskripsi"
								placeholder="Tulis deskripsi dokumen..."
								key="deskripsi"
								required />
						</div>
						<div class="mb-3">
							<x-admin-textinput.file
								label="Upload Dokumen"
								key="file"
								required />
						</div>
					</div>
					<div class="tile-footer">
						<button class="btn btn-primary" type="submit" id="btnSubmit">Tambah</button>&nbsp;&nbsp;&nbsp;
						<button class="btn btn-secondary" type="reset" id="btnReset">Reset</button>
					</div>
				</div>
			</form>
		</div>

		<div class="col-md-5 order-1 order-md-2">
			<div class="tile">
				<embed id="previewFile" src="" type="application/pdf" width="100%" height="400px"
					class="border rounded" />
				<small id="fileName" class="d-block mt-2 text-muted text-center">Tidak ada file dipilih</small>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					<div class="tile-body table-responsive" id="container-table">
						<table class="table table-hover table-bordered" id="myTable">
							<thead>
								<tr>
									<th>#</th>
									<th>Judul Dokumen</th>
									<th>Deskripsi</th>
									<th>Kategori</th>
									<th>Dibuat</th>
									<th>Total Unduhan</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data as $item)
									<tr style="cursor: default"
										data-id="{{ $item->id }}"
										data-judul="{{ $item->judul }}"
										data-deskripsi="{{ $item->deskripsi }}"
										data-kategori_dokumen_id="{{ $item->kategori_dokumen_id }}"
										data-file="{{ $item->file }}">
										<td>{{ $loop->iteration }}.</td>
										<td>{{ $item->judul }}</td>
										<td>{{ $item->deskripsi }}</td>
										<td>{{ $item->kategori->nama }}</td>
										<td>{{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
										<td>
											<a href="{{ asset("storage/$item->dokumen") }}">Lihat Dokumen</a>
										</td>
										<td class="btn-group">
											<form action="{{ route('dashboard.dokumen.destroy', $item->id) }}" method="post">
												@csrf
												@method('delete')
												<button onclick="return confirm('Yakin hapus data ini?')" type="submit" class="btn btn-sm btn-danger"><i
														class="bi bi-trash fs-5"></i></button>
											</form>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>


	@push('scripts')
		<script>
			function previewSelectedFile(input) {
				const file = input.files[0];
				const preview = document.getElementById('previewFile');
				const fileName = document.getElementById('fileName');

				if (!file) {
					preview.removeAttribute('src');
					fileName.textContent = 'Tidak ada file dipilih';
					return;
				}

				const fileURL = URL.createObjectURL(file);
				fileName.textContent = file.name;

				if (file.type === 'application/pdf') {
					preview.setAttribute('type', 'application/pdf');
					preview.setAttribute('src', fileURL);
				} else if (file.name.endsWith('.docx')) {
					preview.setAttribute('type', 'text/html');
					// Google Docs viewer hanya bisa untuk file publik, tapi ini tetap ditampilkan untuk UX
					preview.setAttribute('src', `https://docs.google.com/gview?url=${fileURL}&embedded=true`);
				} else {
					preview.removeAttribute('src');
					fileName.textContent = 'Format file tidak didukung untuk preview';
				}
			}

			document.getElementById('file').addEventListener('change', function() {
				previewSelectedFile(this);
			});

			// Setup klik table to paste
			function setupTableClick() {
				$('#container-table').on('click', '#myTable tbody tr', function(e) {
					if ($(e.target).closest('td').is(':last-child')) return;

					const $row = $(this);
					const dataId = $row.data('id');
					const judul = $row.data('judul');
					const deskripsi = $row.data('deskripsi');
					const file = $row.data('file');
					const kategoriDokumenId = $row.data('kategori_dokumen_id');

					$('#dataId').val(dataId);
					$('#judul').val(judul);
					$('#deskripsi').val(deskripsi);
					$('#kategori_dokumen_id').val(kategoriDokumenId);

					// ✅ Kosongkan input file dan preview
					$('#file').val('');
					$('#previewFile').removeAttr('src');
					$('#fileName').text('Tidak ada file dipilih');

					if (file) {
						const fileExt = file.split('.').pop().toLowerCase();
						const fileUrl = `/storage/${file}`;
						const preview = document.getElementById('previewFile');
						const fileName = document.getElementById('fileName');

						if (fileExt === 'pdf') {
							preview.setAttribute('type', 'application/pdf');
							preview.setAttribute('src', fileUrl);
							fileName.textContent = file.split('/').pop();
						} else if (fileExt === 'docx') {
							preview.setAttribute('type', 'text/html');
							preview.setAttribute('src',
								`https://docs.google.com/gview?url=${window.location.origin}/storage/${file}&embedded=true`
							);
							fileName.textContent = file.split('/').pop();
						} else {
							preview.removeAttribute('src');
							fileName.textContent = 'Format file tidak didukung untuk preview';
						}
					} else {
						document.getElementById('previewFile').removeAttribute('src');
						document.getElementById('fileName').textContent = 'Tidak ada file dipilih';
					}

					const routeTemplate = "{{ route('dashboard.dokumen.update', '__ID__') }}";

					$('form').attr('action', routeTemplate.replace('__ID__', dataId));
					$('#formMethod').val('put');
					$('#btnSubmit').text('Ubah');
					$('#file').attr('required', false);
				});
			}

			function resetForm() {
				$('#btnSubmit').text('Tambah');
				$('#form')[0].reset();
				$('#formMethod').val('post');
				$('form').attr('action', `{{ route('dashboard.dokumen.store') }}`);
				$('#file').attr('required', true);

				// Reset preview
				document.getElementById('previewFile').removeAttribute('src');
				document.getElementById('fileName').textContent = 'Tidak ada file dipilih';
			}

			// Init
			$(document).ready(function() {
				moment.locale('id');
				setupTableClick();

				// Klik reset button
				$('#btnReset').on('click', function() {
					resetForm();
				});
			});
		</script>
	@endpush
</x-layouts.admin>
