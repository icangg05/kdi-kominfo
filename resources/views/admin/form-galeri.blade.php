@php
	$dataId = old('dataId');
	$isEdit = !empty($dataId);

	$action = $isEdit ? route('dashboard.galeri.update', $dataId) : route('dashboard.galeri.store');
@endphp

<x-layouts.admin
	icon="fa fa-image"
	title="Galeri"
	desc="Galeri Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Galeri', '#']]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form id="form" action="{{ $action }}" method="post" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="_method" id="formMethod" value="{{ $isEdit ? 'put' : 'post' }}">
				<input type="hidden" name="dataId" id="dataId" value="{{ old('dataId') }}">
				<input type="hidden" name="gambarPreview" id="gambarPreview" value="{{ old('gambarPreview') }}">
				
				<div class="tile">
					<h3 class="tile-title">Form Galeri</h3>
					<div class="tile-body">
						@csrf
						<div class="mb-3">
							<x-admin-textinput.input
								label="Judul Galeri"
								placeholder="Tulis judul galeri..."
								key="judul"
								required />
						</div>
						<div class="mb-3">
							<x-admin-textinput.input
								label="Tanggal"
								key="tanggal"
								type="date"
								required />
						</div>
						<div>
							<x-admin-textinput.file
								label="Upload Gambar"
								key="gambar"
								:required="$isEdit ? false : true" />
						</div>
					</div>
					<div class="tile-footer">
						<button class="btn btn-primary" type="submit" id="btnSubmit">
							{{ $isEdit ? 'Ubah' : 'Tambah' }}
						</button>&nbsp;&nbsp;&nbsp;
						<button class="btn btn-secondary" type="reset" id="btnReset">Reset</button>
					</div>
				</div>
			</form>
		</div>

		<div class="col-md-5 order-1 order-md-2">
			<div class="tile">
				<img id="previewGambar"
					src="{{ asset(old('gambarPreview') ? 'storage/' . old('gambarPreview') : 'img/berita.webp') }}"
					class="img-thumbnail w-100" alt="image.png">
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
									<th>Judul Galeri</th>
									<th>Tanggal</th>
									<th>Gambar</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data as $item)
									<tr style="cursor: default"
										data-id="{{ $item->id }}"
										data-judul="{{ $item->judul }}"
										data-tanggal="{{ $item->tanggal }}"
										data-gambar="{{ $item->gambar }}">
										<td>{{ $loop->iteration }}.</td>
										<td>{{ $item->judul }}</td>
										<td>{{ Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
										<td>
											<img height="70" src="{{ asset("storage/$item->gambar") }}" alt="image">
										</td>
										<td class="btn-group">
											<form action="{{ route('dashboard.galeri.destroy', $item->id) }}" method="post">
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
					const gambar = $row.data('gambar');

					$('#gambarPreview').val(gambar);

					$('#dataId').val(dataId);
					$('#judul').val(judul);
					$('#tanggal').val(tanggal);
					if (gambar)
						$('#previewGambar').attr('src', `/storage/${gambar}`);
					else
						$('#previewGambar').attr('src', `/img/berita.webp`);

					const routeTemplate = "{{ route('dashboard.galeri.update', ['galeri' => '__ID__']) }}";

					$('form').attr('action', routeTemplate.replace('__ID__', dataId));
					$('#formMethod').val('put');
					$('#btnSubmit').text('Ubah');

					$('#gambar').attr('required', false);
				});
			}

			function resetForm() {
				$('#judul').val("");
				$('#tanggal').val("");
				$('#gambar').val("");
				
				$('.err-message').text("");

				$('#btnSubmit').text('Tambah');
				$('#previewGambar').attr('src', '{{ asset('img/berita.webp') }}');
				$('#formMethod').val('post');
				$('form').attr('action', `{{ route('dashboard.galeri.store') }}`);
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
