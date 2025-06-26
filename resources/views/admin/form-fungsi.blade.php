<x-layouts.admin
	icon="fa fa-cogs"
	title="Fungsi"
	desc="Fungsi Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Fungsi', '#']]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form action="{{ route('admin.profil-dinas.save', 'fungsi') }}" method="post">
				@csrf
				<div class="tile">
					<h3 class="tile-title">Form Fungsi</h3>
					<div class="tile-body">
						<div class="mb-3">
							<x-admin-textinput.textarea
								label="Fungsi Diskominfo"
								placeholder="Tulis fungsi diskominfo..."
								key="konten"
								required />
						</div>
						<div>
							<x-admin-textinput.input
								label="Label Icon"
								placeholder="Tulis nama icon font awesome..."
								key="icon"
								required />
						</div>

						{{-- Tambahkan hidden input untuk misiId --}}
						<input type="hidden" name="misiId" id="misiId">
					</div>
					<div class="tile-footer">
						<button class="btn btn-primary" type="submit" id="btnSubmit">Tambah</button>&nbsp;&nbsp;&nbsp;
						<button class="btn btn-secondary" type="reset" id="btnReset">Reset</button>
					</div>
				</div>
			</form>

		</div>
		@if (session('success'))
			<x-alert type="success" :message="session('success')" />
		@endif
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					<table class="table table-hover table-bordered" id="sampleTable">
						<thead>
							<tr>
								<th>#</th>
								<th>Fungsi Diskominfo</th>
								<th>Icon</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($data->konten as $item)
								<tr style="cursor: default" data-id="{{ $item['id'] }}">
									<td>{{ $loop->iteration }}.</td>
									<td>{{ $item['value'] }}</td>
									<td>
										<i class="{{ $item['icon'] }}"></i>
										<span style="display: none">{{ $item['icon'] }}</span>
									</td>
									<td class="btn-group">
										<form action="{{ route('admin.profil-dinas.delete', 'fungsi') }}" method="post">
											@csrf
											@method('delete')
											<input type="hidden" name="misiId" value="{{ $item['id'] }}">
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


	@push('scripts')
		<script>
			$(document).ready(function() {
				// Saat baris diklik
				$('#sampleTable tbody tr').on('click', function(e) {
					// sekarang 'e' sudah dikenali
					if ($(e.target).closest('td').is(':last-child'))
						return;

					let misiText = $(this).find('td:nth-child(2)').text().trim();
					let misiId = $(this).data('id');
					
					let iconText = $(this).find('td:nth-child(3) span').text().trim();

					// Isi ke form
					$('#konten').val(misiText);
					$('#misiId').val(misiId);
					$('#icon').val(iconText);
					$('#btnSubmit').text('Ubah');
				});

				// Reset form
				$('#btnReset').on('click', function() {
					$('#btnSubmit').text('Tambah');
					$('#misiId').val('');
				});
			});
		</script>
	@endpush

</x-layouts.admin>
