<x-layouts.admin
	icon="fa fa-book"
	title="Visi"
	desc="Visi Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Visi', '#']]">

	<div class="row">
		<div class="col-md-7 order-2 order-md-1">
			<form action="" method="post">
				<div class="tile">
					<h3 class="tile-title">Form Visi</h3>
					<div class="tile-body">
						@csrf
						<div>
							<x-admin-textinput.ckeditor
								label="Tulis Visi Diskominfo"
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
