<x-layouts.admin
	icon="fas fa-quote-left"
	title="Tagline"
	desc="Tagline Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Tagline', '#']]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form id="form" action="{{ route('dashboard.profil-dinas.save', 'tagline-sambutan') }}" method="post">
				@csrf
				<div class="tile">
					<h3 class="tile-title">Form Tagline</h3>
					<div class="tile-body">
						<x-admin-textinput.input
							placeholder="Tulis tagline..."
							key="konten"
							required />

						{{-- Tambahkan hidden input untuk dataId --}}
						<input type="hidden" name="dataId" id="dataId">
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
									<th>Tagline Diskominfo</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data->konten as $item)
									<tr style="cursor: default"
										data-id="{{ $item['id'] }}"
										data-value="{{ $item['value'] }}">
										<td>{{ $loop->iteration }}.</td>
										<td>{{ $item['value'] }}</td>
										<td class="btn-group">
											<form action="{{ route('dashboard.profil-dinas.delete', 'tagline-sambutan') }}" method="post">
												@csrf
												@method('delete')
												<input type="hidden" name="dataId" value="{{ $item['id'] }}">
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
</x-layouts.admin>
