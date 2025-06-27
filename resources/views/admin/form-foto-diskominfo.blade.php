<x-layouts.admin
	icon="fa fa-image"
	title="Foto Diskominfo"
	desc="Foto Diskominfo Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Foto Diskominfo', '#']]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form action="{{ route('admin.profil-dinas.save', 'foto-diskominfo') }}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="tile">
					<h3 class="tile-title">Form Foto Diskominfo</h3>
					<div class="tile-body">
						<x-admin-textinput.file
							label="Upload Foto"
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
					<div style="columns: 200px;">
						@foreach ($data->konten as $item)
							<div style="position: relative;" class="mb-3">
								<img class="img-fluid" src="{{ asset('storage/' . $item['value']) }}" alt="{{ $item['id'] . '.jpg' }}">
								<form action="{{ route('admin.profil-dinas.delete', 'foto-diskominfo') }}" method="post"
									style="position: absolute; top:0; right: 0;">
									@csrf
									@method('delete')

									<input type="hidden" name="dataId" value="{{ $item['id'] }}">
									<button onclick="return confirm('Yakin hapus data ini>')" class="btn btn-sm btn-danger">
										<i class="fa fa-trash"></i>
									</button>
								</form>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</x-layouts.admin>
