<x-layouts.admin
	icon="fa fa-university"
	title="Sambutan Kepala Dinas"
	desc="Sambutan Kepala Dinas Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Sambutan Kepala Dinas', '#'], ]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form action="{{ route('dashboard.profil-dinas', 'sambutan-kadis') }}" method="post">
        @csrf
				<div class="tile">
					<h3 class="tile-title">Form Sambutan Kepala Dinas</h3>
					<div class="tile-body">
						@csrf
						<div>
							<x-admin-textinput.ckeditor
								label="Tulis Sambutan Kepala Dinas"
								key="konten"
								:value="$data->konten" />
						</div>
					</div>
					<div class="tile-footer">
						<button class="btn btn-primary" type="submit">Simpan</button>&nbsp;&nbsp;&nbsp;
						<button class="btn btn-secondary" type="reset">Reset</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</x-layouts.admin>
