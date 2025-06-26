<x-layouts.admin
	icon="fa fa-university"
	title="Sejarah"
	desc="Sejarah Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Sejarah', '#'], ]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form action="" method="post">
				<div class="tile">
					<h3 class="tile-title">Form Sejarah</h3>
					<div class="tile-body">
						@csrf
						<div>
							<x-admin-textinput.ckeditor
								label="Tulis Sejarah Diskominfo"
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
		@if (session('success'))
			<x-alert type="success" :message="session('success')" />
		@endif

	</div>
</x-layouts.admin>
