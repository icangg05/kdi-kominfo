<x-layouts.admin
	icon="fas fa-gauge" {{-- fa-dashboard sudah tidak ada di FA6 --}}
	title="Dashboard"
	desc="Selamat datang kembali, {{ auth()->user()->name }}"
	:nav-menu="[['Dashboard', '#']]">
	<div class="row">
		<div class="col-md-6 col-lg-3">
			<a href="{{ route('dashboard.pegawai') }}">
				<div class="widget-small primary coloured-icon">
					<i class="icon fas fa-users fa-3x"></i>
					<div class="info">
						<h4>Pegawai</h4>
						<p><b>{{ number_format($totalPegawai, 0, ',', '.') }}</b></p>
					</div>
				</div>
			</a>
		</div>
		<div class="col-md-6 col-lg-3">
			<a href="{{ route('dashboard.berita.index') }}">
				<div class="widget-small info coloured-icon">
					<i class="icon fas fa-newspaper fa-3x"></i>
					<div class="info">
						<h4>Berita</h4>
						<p><b>{{ number_format($totalBerita, 0, ',', '.') }}</b></p>
					</div>
				</div>
			</a>
		</div>
		<div class="col-md-6 col-lg-3">
			<a href="{{ route('dashboard.dokumen.index') }}">
				<div class="widget-small warning coloured-icon">
					<i class="icon fas fa-file-lines fa-3x"></i>
					<div class="info">
						<h4>Dokumen</h4>
						<p><b>{{ number_format($totalDokumen, 0, ',', '.') }}</b></p>
					</div>
				</div>
			</a>
		</div>
		<div class="col-md-6 col-lg-3">
			<a href="{{ route('dashboard.galeri.index') }}">
				<div class="widget-small danger coloured-icon">
					<i class="icon fas fa-image fa-3x"></i>
					<div class="info">
						<h4>Galeri Diskominfo</h4>
						<p><b>{{ number_format($totalGaleri, 0, ',', '.') }}</b></p>
					</div>
				</div>
			</a>
		</div>
	</div>
</x-layouts.admin>
