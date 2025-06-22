<x-layouts.page-berita
	:breadcrumb="[['fas fa-file-signature', 'Layanan', '#'], ['Layanan Pengajuan TTE', '#']]"
	:title="'Layanan Pengajuan TTE'"
	:desc="'Layanan pengajuan Tanda Tangan Elektronik (TTE) untuk dokumen resmi yang diakui secara hukum dan terverifikasi oleh Balai Sertifikasi Elektronik (BSrE).'">

	<div class="bg-white rounded-xl c2Shadow p-6 space-y-12 text-gray-700 text-sm md:text-base leading-relaxed">

		<!-- Header & Ilustrasi -->
		<div class="grid lg:grid-cols-2 gap-10 lg:gap-0 items-center">
			<div>
				<h2 class="text-2xl font-bold text-primary mb-4">Tanda Tangan Elektronik (TTE)</h2>
				<p>
					Tanda Tangan Elektronik (TTE) merupakan solusi digital untuk otorisasi dokumen yang sah, aman, dan memiliki
					kekuatan hukum. Dinas Kominfo Kota Kendari memfasilitasi layanan ini untuk perangkat daerah guna mendukung proses
					birokrasi yang lebih efisien dan modern.
				</p>

				<!-- Ajakan -->
				<div class="mt-6 bg-primary/10 text-primary border-l-4 border-primary p-4 rounded-md">
					<i class="fas fa-check-circle mr-2"></i>
					<span><strong>Segera ajukan TTE</strong> untuk efisiensi dokumen dinas yang lebih cepat dan aman.</span>
				</div>
			</div>
			<div class="flex justify-center">
				<img src="{{ asset('img/signature.svg') }}" alt="Ilustrasi TTE"
					class="w-full max-w-md object-contain">
			</div>
		</div>

		<!-- Manfaat TTE -->
		<section>
			<h2 class="text-lg font-semibold text-primary mb-4">Keuntungan Menggunakan TTE</h2>
			<div class="grid md:grid-cols-3 gap-6">
				@php
					$manfaat = [
					    [
					        'icon' => 'fa-lock',
					        'judul' => 'Keamanan Tinggi',
					        'deskripsi' => 'Dokumen terlindungi dengan enkripsi dan tidak dapat dipalsukan.',
					    ],
					    [
					        'icon' => 'fa-clock',
					        'judul' => 'Efisiensi Waktu',
					        'deskripsi' => 'Proses tanda tangan lebih cepat tanpa perlu cetak atau kirim fisik.',
					    ],
					    [
					        'icon' => 'fa-globe',
					        'judul' => 'Legal Nasional',
					        'deskripsi' => 'TTE diakui oleh BSrE dan sah menurut hukum Indonesia.',
					    ],
					];
				@endphp

				@foreach ($manfaat as $item)
					<div class="bg-primary/10 p-4 border-l-4 border-primary rounded-md flex flex-col gap-2">
						<i class="fas {{ $item['icon'] }} text-2xl text-primary"></i>
						<h3 class="text-base font-semibold">{{ $item['judul'] }}</h3>
						<p class="text-sm text-gray-700">{{ $item['deskripsi'] }}</p>
					</div>
				@endforeach
			</div>
		</section>

		<div class="flex flex-col lg:flex-row gap-8 lg:gap-0">
			<!-- Persyaratan -->
			<section class="basis-1/2">
				<h2 class="text-lg font-semibold text-primary mb-4">Persyaratan Pengajuan TTE</h2>
				<ul class="list-disc pl-6 space-y-2">
					<li>Surat permohonan dari pimpinan perangkat daerah.</li>
					<li>Scan KTP dan NPWP pejabat yang mengajukan TTE.</li>
					<li>Alamat email resmi yang aktif.</li>
					<li>Dokumen atau format TTE yang akan digunakan.</li>
				</ul>
			</section>

			<!-- Alur Pengajuan -->
			<section>
				<h2 class="text-lg font-semibold text-primary mb-4">Alur Pengajuan TTE</h2>
				<ol class="list-decimal pl-6 space-y-2">
					<li>OPD mengirimkan permohonan ke Dinas Kominfo Kendari.</li>
					<li>Verifikasi data dan kelengkapan oleh tim TTE.</li>
					<li>Registrasi ke Balai Sertifikasi Elektronik (BSrE).</li>
					<li>Akun TTE dikirimkan ke pemohon untuk digunakan.</li>
				</ol>
			</section>
		</div>

		<!-- Kontak -->
		<section class="bg-primary/10 p-6 border-l-4 border-primary rounded-md">
			<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
				<div>
					<h2 class="text-lg font-semibold text-primary mb-1">
						<i class="fas fa-info-circle mr-2"></i>Informasi dan Bantuan
					</h2>
					<p class="text-sm text-primary/90">Hubungi kami jika Anda memerlukan informasi lebih lanjut atau bantuan teknis
						terkait pengajuan TTE.</p>
				</div>
				<div class="left lg:text-right space-y-2">
					<p class="text-sm text-gray-800">
						<i class="fas fa-envelope text-primary mr-1"></i>
						<a class="text-blue-600 hover:underline" href="mailto:kominfo@kendarikota.go.id">kominfo@kendarikota.go.id</a>
					</p>
					<p class="text-sm text-gray-800"><i class="fas fa-phone-alt text-primary mr-1"></i> (0401) 1234567</p>
					<p class="text-sm text-gray-800"><i class="fas fa-map-marker-alt text-primary mr-1"></i> Jl. Balaikota Blok A,
						Kendari</p>
				</div>
			</div>
		</section>

	</div>
</x-layouts.page-berita>
