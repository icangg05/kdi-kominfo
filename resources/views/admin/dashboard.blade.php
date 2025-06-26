<x-layouts.admin
	icon="fa fa-dashboard"
	title="Dashboard"
	desc="Selamat datang kembali, {{ auth()->user()->name }}"
	:nav-menu="[['Dashboard', '#']]">
	<div class="row">
		<div class="col-md-6 col-lg-3">
			<div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
				<div class="info">
					<h4>Pegawai</h4>
					<p><b>{{ number_format($totalPegawai, 0, ',', '.') }}</b></p>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-3">
			<div class="widget-small info coloured-icon"><i class="icon fa fa-file-text-o fa-3x"></i>
				<div class="info">
					<h4>Berita</h4>
					<p><b>{{ number_format($totalBerita, 0, ',', '.') }}</b></p>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-3">
			<div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
				<div class="info">
					<h4>Dokumen</h4>
					<p><b>{{ number_format($totalDokumen, 0, ',', '.') }}</b></p>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-3">
			<div class="widget-small danger coloured-icon"><i class="icon fa fa-picture-o fa-3x"></i>
				<div class="info">
					<h4>Galeri Diskominfo</h4>
					<p><b>{{ number_format($totalGaleri, 0, ',', '.') }}</b></p>
				</div>
			</div>
		</div>
	</div>
</x-layouts.admin>
