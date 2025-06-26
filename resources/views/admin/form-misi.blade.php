<x-layouts.admin
	icon="fa-clock-rotate-left"
	title="Misi"
	desc="Misi Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Misi', '#']]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form action="{{ route('admin.profil-dinas.save', 'misi') }}" method="post" id="formMisi">
				@csrf
				<div class="tile">
					<h3 class="tile-title">Form Misi</h3>
					<div class="tile-body">
						<x-admin-textinput.textarea
							placeholder="Tulis misi diskominfo..."
							key="konten"
							id="konten"
							required />

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
								<th>Misi Diskominfo</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($data->konten as $item)
								<tr style="cursor: default" data-id="{{ $item['id'] }}">
									<td>{{ $loop->iteration }}.</td>
									<td>{{ $item['misi'] }}</td>
									<td class="btn-group">
										<form action="{{ route('admin.profil-dinas.delete', 'misi') }}" method="post">
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

					// Isi ke form
					$('#konten').val(misiText);
					$('#misiId').val(misiId);
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
