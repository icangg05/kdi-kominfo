<x-layouts.admin
	icon="fa fa-cog"
	title="Pengaturan"
	desc="Pengaturan Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Pengaturan', '#']]">

	<div class="row">
		<div class="col-md-8">
			<div class="tile">
				<form id="form" action="{{ route('dashboard.pengaturan.update') }}" method="post">
					@csrf
					<h3 class="tile-title">Form Pengaturan</h3>
					<div class="tile-body">

						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.input
										label="No. Telpon"
										placeholder="Tulis no. telpon..."
										key="telp"
										:value="$telp"
										required />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.input
										label="Email Diskominfo"
										placeholder="Tulis email diskominfo..."
										key="email"
										:value="$email"
										required />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.input
										label="Link Facebook"
										placeholder="Tulis link akun facebook..."
										:value="$fb"
										key="fb" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.input
										label="Link Instagram"
										placeholder="Tulis link akun instagram..."
										:value="$ig"
										key="ig" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.input
										label="Link Tiktok"
										placeholder="Tulis link akun tiktok..."
										:value="$tt"
										key="tt" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.input
										label="Email Diskominfo"
										placeholder="Tulis email diskominfo..."
										:value="$yt"
										key="yt" />
								</div>
							</div>
						</div>
					</div>
					<div class="tile-footer">
						<button class="btn btn-primary" type="submit" id="btnSubmit">Simpan</button>&nbsp;&nbsp;&nbsp;
						<button class="btn btn-secondary" type="reset">Reset</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</x-layouts.admin>
