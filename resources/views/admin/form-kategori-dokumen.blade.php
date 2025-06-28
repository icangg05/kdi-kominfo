<x-layouts.admin
	icon="fa fa-file"
	title="Kategori Dokumen"
	desc="Kategori Dokumen Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Kategori Dokumen', '#']]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form id="form" action="{{ route('dashboard.kategori-dokumen.store') }}" method="post">
				@csrf
				<div class="tile">
					<h3 class="tile-title">Form Kategori Dokumen</h3>
					<div class="tile-body">
						<div>
							<x-admin-textinput.input
								label="Nama Kategori"
								placeholder="Tulis nama kategori dokumen..."
								key="nama"
								required />
						</div>

						{{-- Tambahkan hidden input untuk dataId --}}
						<input type="hidden" name="dataId" id="dataId">
						<input type="hidden" name="_method" id="formMethod" value="post">
					</div>
					<div class="tile-footer">
						<button class="btn btn-primary" type="submit" id="btnSubmit">Tambah</button>&nbsp;&nbsp;&nbsp;
						<button class="btn btn-secondary" type="reset" id="btnReset">Reset</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					<div class="table-responsive" id="container-table">
						<table class="table table-hover table-bordered" id="sampleTable">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama Kategori</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data as $item)
									<tr style="cursor: default" data-id="{{ $item['id'] }}">
										<td>{{ $loop->iteration }}.</td>
										<td>{{ $item->nama }}</td>
										<td class="btn-group">
											<form action="{{ route('dashboard.kategori-dokumen.destroy', $item->id) }}" method="post">
												@csrf
												@method('delete')
												<button onclick="return confirm('Yakin hapus data ini?')" type="submit" class="btn btn-sm btn-danger"><i
														class="bi bi-trash fs-5"></i></button>
											</form>
										</td>
									</tr>
								@endforeach
							</tbody>
	
							<script>
								$(document).ready(function() {
									// Saat baris diklik
									$('#sampleTable').on('click', 'tbody tr', function(e) {
										if ($(e.target).closest('td').is(':last-child')) return;
	
										let valueText = $(this).find('td:nth-child(2)').text().trim();
										let dataId = $(this).data('id');
	
										$('#nama').val(valueText);
										$('#dataId').val(dataId);
										$('#btnSubmit').text('Ubah');
										$('#formMethod').val('put');
	
										// Ganti action ke URL update dengan ID
										$('form').attr('action', `/dashboard/kategori-dokumen/${dataId}`);
									});
	
									// Reset form
									$('#btnReset').on('click', function() {
										$('#btnSubmit').text('Tambah');
										$('#dataId').val('');
										$('#formMethod').val('post'); // reset ke POST
										$('form').attr('action', `{{ route('dashboard.kategori-dokumen.store') }}`);
									});
								});
							</script>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

</x-layouts.admin>
