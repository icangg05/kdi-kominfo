<x-layouts.page-berita
	:breadcrumb="[['fas fa-envelope', 'Layanan', '#'], ['Layanan Email Dinas', '#']]"
	:title="'Layanan Pembuatan Email Dinas'"
	:desc="'Layanan pembuatan akun email resmi dengan domain @kendarikota.go.id untuk perangkat daerah di lingkungan Pemerintah Kota Kendari.'">

	<div class="bg-white rounded-xl c2Shadow p-6 space-y-12 text-gray-700 text-sm md:text-base leading-relaxed">

		<!-- Header & Ilustrasi -->
		<div class="grid lg:grid-cols-2 gap-10 lg:gap-0 items-center">
			<div>
				<h2 class="text-2xl font-bold text-primary mb-4">Email Resmi Pemerintah Daerah</h2>
				<p>
					Dinas Komunikasi dan Informatika Kota Kendari menyediakan layanan pembuatan email dinas dengan domain
					<strong>@kendarikota.go.id</strong>. Email ini digunakan untuk komunikasi resmi, surat menyurat elektronik,
					dan keperluan administrasi digital perangkat daerah.
				</p>

				<!-- Ajakan -->
				<div class="mt-6 bg-primary/10 text-primary border-l-4 border-primary p-4 rounded-md">
					<i class="fas fa-check-circle mr-2"></i>
					<span><strong>Gunakan email resmi</strong> untuk meningkatkan profesionalitas dan keamanan komunikasi dinas
						Anda.</span>
				</div>
			</div>
			<div class="flex justify-center">
				<img src="{{ asset('img/email-official.svg') }}" alt="Ilustrasi Email Dinas"
					class="w-[50%] max-w-xs object-contain">
			</div>
		</div>

		<!-- Manfaat Email Dinas -->
		<section>
			<h2 class="text-lg font-semibold text-primary mb-4">Mengapa Menggunakan Email Dinas?</h2>
			<div class="grid md:grid-cols-3 gap-6">
				@php
					$manfaat = [
					    [
					        'icon' => 'fa-shield-alt',
					        'judul' => 'Keamanan Data',
					        'deskripsi' => 'Akun email dinas memiliki tingkat perlindungan dan kontrol administratif yang lebih baik.',
					    ],
					    [
					        'icon' => 'fa-envelope-open-text',
					        'judul' => 'Identitas Resmi',
					        'deskripsi' => 'Menggunakan domain .go.id menandakan keabsahan institusi pemerintah.',
					    ],
					    [
					        'icon' => 'fa-sync',
					        'judul' => 'Terintegrasi',
					        'deskripsi' => 'Bisa terintegrasi dengan layanan pemerintah dan platform digital lainnya.',
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

		<!-- Persyaratan & Prosedur -->
		<div class="flex flex-col lg:flex-row gap-8">
			<!-- Persyaratan -->
			<section class="basis-1/2">
				<h2 class="text-lg font-semibold text-primary mb-4">Persyaratan Pengajuan</h2>
				<ul class="list-disc pl-6 space-y-2">
					<li>Surat permohonan dari pimpinan OPD.</li>
					<li>Daftar nama dan jabatan yang akan dibuatkan email.</li>
					<li>Nomor HP dan email aktif sebagai kontak verifikasi.</li>
					<li>Usulan format email (misal: nama@kendarikota.go.id).</li>
				</ul>
			</section>

			<!-- Prosedur -->
			<section class="basis-1/2">
				<h2 class="text-lg font-semibold text-primary mb-4">Prosedur Pengajuan</h2>
				<ol class="list-decimal pl-6 space-y-2">
					<li>OPD mengirim permohonan ke Dinas Kominfo Kendari.</li>
					<li>Tim memverifikasi data dan kebutuhan.</li>
					<li>Akun dibuat dan disampaikan ke pemohon.</li>
					<li>OPD dapat langsung menggunakan email dinas secara aktif.</li>
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
					<p class="text-sm text-primary/90">Hubungi kami untuk konsultasi atau pengajuan pembuatan akun email dinas resmi.
					</p>
				</div>
				<div class="text-left lg:text-right space-y-2">
					<p class="text-sm text-gray-800"><i class="fas fa-envelope text-primary mr-1"></i> kominfo@kendarikota.go.id</p>
					<p class="text-sm text-gray-800"><i class="fas fa-phone-alt text-primary mr-1"></i> (0401) 1234567</p>
					<p class="text-sm text-gray-800"><i class="fas fa-map-marker-alt text-primary mr-1"></i> Jl. Balaikota Blok A,
						Kendari</p>
				</div>
			</div>
		</section>

	</div>
</x-layouts.page-berita>
