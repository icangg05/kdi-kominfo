<x-layouts.page-berita
	:breadcrumb="[['fas fa-globe', 'Layanan', '#'], ['Subdomain & Hosting', '#']]"
	:title="'Subdomain & Hosting'"
	:desc="'Layanan pengelolaan subdomain dan hosting untuk mendukung kebutuhan situs web perangkat daerah di lingkungan Pemerintah Kota Kendari.'">

	<div class="bg-white rounded-xl c2Shadow p-6 space-y-10 leading-relaxed text-gray-700 text-sm md:text-base">

		<!-- Banner Ilustrasi -->
		<div class="w-full bg-[#073354] rounded-lg">
			<img src="{{ asset('img/subdomain.webp') }}" alt="Ilustrasi Subdomain Hosting"
				class="w-full max-h-72 object-contain rounded-lg">
		</div>

		<!-- Tentang -->
		<section class="flex flex-col md:flex-row gap-12 items-center">
			<div class="basis-2/3">
				<h2 class="text-lg font-semibold text-primary mb-2">Tentang Layanan</h2>
				<p>
					Dinas Komunikasi dan Informatika Kota Kendari menyediakan layanan <strong>Subdomain dan Hosting</strong> bagi
					perangkat daerah yang membutuhkan media publikasi berbasis web.
					Layanan ini mendukung keterbukaan informasi publik dan identitas digital melalui domain resmi seperti
					<code>namaopd.kendarikota.go.id</code>.
				</p>

				<!-- Fitur -->
				<section class="mt-8">
					<h2 class="text-lg font-semibold text-primary mb-2">Fitur yang Tersedia</h2>
					<div class="grid md:grid-cols-2 gap-4">
						@php
							$fitur = [
							    ['icon' => 'fa-globe', 'text' => 'Subdomain .kendarikota.go.id'],
							    ['icon' => 'fa-hdd', 'text' => 'Hosting sesuai kapasitas'],
							    ['icon' => 'fa-cogs', 'text' => 'Instalasi CMS (WordPress, Laravel)'],
							    ['icon' => 'fa-tools', 'text' => 'Akses panel hosting'],
							    ['icon' => 'fa-shield-alt', 'text' => 'Pemantauan uptime & keamanan'],
							];
						@endphp
						@foreach ($fitur as $f)
							<div class="flex items-start gap-3 bg-gray-50 p-4 rounded-md border border-gray-200">
								<i class="fas {{ $f['icon'] }} text-primary mt-1 text-lg"></i>
								<p>{{ $f['text'] }}</p>
							</div>
						@endforeach
					</div>
				</section>
			</div>
			<div class="hidden basis-1/3 pr-10 lg:block">
				<img src="{{ asset('img/server-cluster.svg') }}" alt="Ilustrasi Hosting"
					class="w-[90%] lg:w-full object-cover">
			</div>
		</section>

		<!-- Persyaratan & Prosedur -->
		<section class="grid md:grid-cols-2 gap-8">
			<div>
				<h2 class="text-lg font-semibold text-primary mb-2">Persyaratan Pengajuan</h2>
				<ul class="list-decimal pl-5 space-y-1">
					<li>Surat Permohonan Resmi dari Pimpinan Perangkat Daerah.</li>
					<li>Deskripsi tujuan penggunaan subdomain.</li>
					<li>Usulan nama subdomain.</li>
					<li>Kontak teknis dari OPD.</li>
				</ul>
			</div>
			<div>
				<h2 class="text-lg font-semibold text-primary mb-2">Prosedur Pengajuan</h2>
				<ol class="list-decimal pl-5 space-y-1">
					<li>OPD mengirimkan surat ke Dinas Kominfo.</li>
					<li>Tim melakukan verifikasi dan klarifikasi.</li>
					<li>Subdomain & hosting disiapkan bila disetujui.</li>
					<li>Akun & akses diberikan ke OPD.</li>
				</ol>
			</div>
		</section>

		<!-- Kontak -->
		<section class="bg-primary/5 border-l-4 border-primary p-5 rounded-md">
			<h2 class="text-lg font-semibold text-primary mb-2">Hubungi Kami</h2>
			<p class="mb-3">Untuk pertanyaan atau pengajuan layanan, silakan hubungi:</p>
			<ul class="space-y-1 text-sm">
				<li><i class="fas fa-envelope text-primary mr-1"></i> Email: <a href="mailto:kominfo@kendarikota.go.id"
						class="text-blue-600 hover:underline">kominfo@kendarikota.go.id</a></li>
				<li><i class="fas fa-phone-alt text-primary mr-1"></i> Telepon: (0401) 1234567</li>
				<li><i class="fas fa-map-marker-alt text-primary mr-1"></i> Jl. Balaikota Blok A, Kendari</li>
			</ul>
		</section>
	</div>

</x-layouts.page-berita>
