<x-layouts.admin
	icon="fa fa-sitemap"
	title="Struktur Organisasi"
	desc="Struktur Organisasi Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Struktur Organisasi', '#']]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form action="{{ route('admin.profil-dinas', 'struktur-organisasi') }}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="tile">
					<h3 class="tile-title">Form Struktur Organisasi</h3>
					<div class="tile-body">
						@csrf
						<div>
							<x-admin-textinput.file
								key="konten"
								required />
						</div>
					</div>
					<div class="tile-footer">
						<button class="btn btn-primary" type="submit">Ubah</button>&nbsp;&nbsp;&nbsp;
						<button class="btn btn-secondary" type="reset">Reset</button>
					</div>
				</div>
			</form>
		</div>

		<div class="col-md-5 order-1 order-md-2">
			<div class="tile">
				<img src="{{ asset("storage/$data->konten") }}" class="img-thumbnail" alt="struktur.png">
			</div>
		</div>
	</div>
</x-layouts.admin>
