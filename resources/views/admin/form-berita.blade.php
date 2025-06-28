<x-layouts.admin
	icon="fa fa-newspaper"
	title="Berita"
	desc="Berita Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Berita', '#']]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form id="form" action="{{ route('dashboard.berita.store') }}" method="post" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="_method" id="formMethod" value="post">
				<div class="tile">
					<h3 class="tile-title">Form Berita</h3>
					<div class="tile-body">
						@csrf
						<div class="mb-3">
							<x-admin-textinput.input
								label="Judul Berita"
								placeholder="Tulis judul berita..."
								key="judul"
								required />
						</div>
						<div class="row">
							<div class="col-md-6">
								<x-admin-textinput.input
									label="Tanggal"
									key="tanggal"
									type="date"
									required />
							</div>
							<div class="col-md-6">
								<x-admin-textinput.select
									:data-select="App\Models\KategoriBerita::pluck('nama', 'id')"
									label="Kategori"
									key="kategori_berita_id"
									required />
							</div>
						</div>
						<div class="mb-3">
							<x-admin-textinput.file
								label="Upload Gambar"
								key="thumbnail" />
						</div>
						<div>
							<x-admin-textinput.ckeditor
								label="Konten"
								key="konten" />
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
				<img id="previewGambar" src="{{ asset('img/berita.webp') }}" class="img-thumbnail w-100" alt="image.png">
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
									<th>Judul Berita</th>
									<th>Tanggal</th>
									<th>Kategori</th>
									<th>Thumbnail</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data as $item)
									<tr style="cursor: default"
										data-id="{{ $item->id }}"
										data-judul="{{ $item->judul }}"
										data-tanggal="{{ $item->tanggal }}"
										data-kategori_berita_id="{{ $item->kategori_berita_id }}"
										data-thumbnail="{{ $item->thumbnail }}"
										data-konten="{{ $item->konten }}">
										<td>{{ $loop->iteration }}.</td>
										<td>{{ $item->judul }}</td>
										<td>{{ Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
										<td>{{ $item->Kategori->nama }}</td>
										<td>
											<img height="70"
												src="{{ asset($item->thumbnail ? "storage/$item->thumbnail" : 'img/berita.webp') }}" alt="image">
										</td>
										<td class="btn-group">
											<form action="{{ route('dashboard.berita.destroy', $item->id) }}" method="post">
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
			// Setup klik table to paste
			function setupTableClick() {
				$('#container-table').on('click', '#myTable tbody tr', function(e) {
					if ($(e.target).closest('td').is(':last-child')) return;

					const $row = $(this);
					const dataId = $row.data('id');
					const judul = $row.data('judul');
					const tanggal = $row.data('tanggal');
					const thumbnail = $row.data('thumbnail');
					const kategoriBeritaId = $row.data('kategori_berita_id');
					const konten = $row.data('konten');
					if (window.editor) {
						window.editor.setData(konten);
					}

					$('#dataId').val(dataId);
					$('#judul').val(judul);
					$('#konten').val(konten);
					$('#tanggal').val(tanggal);
					$('#tanggal_lahir').val(moment(tanggal, 'D MMMM YYYY').format('YYYY-MM-DD'));
					$('#kategori_berita_id').val(kategoriBeritaId);
					if (thumbnail)
						$('#previewGambar').attr('src', `/storage/${thumbnail}`);
					else
						$('#previewGambar').attr('src', `/img/berita.webp`);

					const routeTemplate = "{{ route('dashboard.berita.update', '__ID__') }}";

					$('form').attr('action', routeTemplate.replace('__ID__', dataId));
					$('#formMethod').val('put');
					$('#btnSubmit').text('Ubah');

					$('#gambar').attr('required', false);
				});
			}

			function resetForm() {
				$('#btnSubmit').text('Tambah');
				$('#previewGambar').attr('src', '{{ asset('img/berita.webp') }}');
				$('#form')[0].reset();
				$('#formMethod').val('post');
				$('form').attr('action', `{{ route('dashboard.berita.store') }}`);
				$('#gambar').attr('required', true);
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
