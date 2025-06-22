<div>
	<x-layouts.page-berita
		:breadcrumb="[['fas fa-phone-volume', 'Layanan', '#'], ['Layanan Telpon Darurat 112', '#']]"
		:title="'Layanan Telpon Darurat 112'"
		:desc="'Layanan panggilan bebas pulsa 112 untuk keadaan darurat di wilayah Kota Kendari, aktif 24 jam dan cepat tanggap.'">

		<div class="bg-white rounded-xl c2Shadow p-6 space-y-12 text-gray-700 text-sm md:text-base leading-relaxed">

			<!-- Ilustrasi Utama -->
			<div class="flex flex-col lg:flex-row gap-8 lg:gap-0 items-center">
				<div class="basis-1/2">
					<img src="{{ asset('img/emergency.webp') }}" alt="Ilustrasi Layanan 112"
						class="w-full max-w-md mx-auto lg:mx-0">
				</div>
				<div class="basis-1/2">
					<h2 class="text-2xl font-bold text-primary mb-3">Apa itu Layanan 112?</h2>
					<p>
						Layanan <strong>Telpon Darurat 112</strong> adalah layanan panggilan cepat yang dapat digunakan oleh masyarakat
						Kota Kendari untuk melaporkan berbagai keadaan darurat seperti kebakaran, kecelakaan, gangguan keamanan, dan
						keadaan gawat darurat lainnya. Layanan ini tidak memerlukan pulsa dan tersedia selama <strong>24 jam</strong>.
					</p>

					<!-- Jenis Keadaan Darurat -->
					<section class="mt-8">
						<h2 class="text-lg font-semibold text-primary mb-4 leading-snug">
							Apa Saja Keadaan Darurat yang Bisa Dilaporkan?
						</h2>
						<ul class="space-y-1 lg:space-y-2 pl-4 list-disc text-gray-700">
							<li>Kebakaran rumah, gedung, atau hutan.</li>
							<li>Kecelakaan lalu lintas di jalan raya.</li>
							<li>Orang hilang atau membutuhkan evakuasi medis.</li>
							<li>Kriminalitas atau ancaman keamanan.</li>
							<li>Bencana alam seperti banjir dan tanah longsor.</li>
						</ul>
					</section>
				</div>
			</div>

			<!-- Keunggulan -->
			<section>
				<h2 class="text-lg font-semibold text-primary mb-4 leading-snug">Mengapa Menggunakan 112?</h2>
				<div class="grid md:grid-cols-3 gap-6">
					@php
						$keunggulan = [
						    [
						        'icon' => 'fa-headset',
						        'judul' => 'Respon Cepat',
						        'deskripsi' => 'Tim operator dan instansi terkait akan segera merespon setiap laporan yang masuk.',
						    ],
						    [
						        'icon' => 'fa-clock',
						        'judul' => 'Layanan 24/7',
						        'deskripsi' => 'Dapat digunakan kapan saja, termasuk di malam hari dan hari libur.',
						    ],
						    [
						        'icon' => 'fa-phone-slash',
						        'judul' => 'Bebas Pulsa',
						        'deskripsi' => 'Panggilan ke 112 tidak memerlukan pulsa, bahkan dari HP tanpa kartu SIM.',
						    ],
						];
					@endphp

					@foreach ($keunggulan as $item)
						<div class="bg-primary/5 p-5 rounded-lg border border-primary/10 flex flex-col items-start gap-3">
							<i class="fas {{ $item['icon'] }} text-2xl text-primary"></i>
							<h3 class="text-base font-semibold text-gray-800">{{ $item['judul'] }}</h3>
							<p class="text-sm text-gray-600">{{ $item['deskripsi'] }}</p>
						</div>
					@endforeach
				</div>
			</section>

			<!-- Langkah Melapor -->
			<section>
				<h2 class="text-lg font-semibold text-primary mb-4 leading-snug">Cara Melaporkan Melalui 112</h2>
				<ol class="list-decimal pl-6 space-y-2">
					<li>Tekan 112 di ponsel Anda, tanpa perlu pulsa.</li>
					<li>Sampaikan informasi darurat secara singkat dan jelas.</li>
					<li>Sebutkan lokasi kejadian secara akurat.</li>
					<li>Ikuti arahan dari petugas layanan darurat.</li>
				</ol>
			</section>

			<!-- Kontak & Informasi Tambahan -->
			<section class="bg-primary/10 p-6 border-l-4 border-primary rounded-md">
				<div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
					<div>
						<h2 class="text-lg font-semibold text-primary mb-1">Butuh Bantuan Sekarang?</h2>
						<p class="text-sm lg:text-base text-primary/90">Layanan siaga 24 jam untuk keadaan darurat di Kota Kendari:</p>
					</div>
					<div class="text-center md:text-right space-y-2">
						<p class="text-3xl font-bold text-primary"><i class="fas fa-phone-alt mr-2"></i>112</p>
						<p class="text-sm lg:text-base text-gray-600">Gratis & tanpa pulsa – bahkan tanpa kartu SIM</p>
						<a href="tel:112"
							class="inline-flex items-center gap-2 mt-2 px-4 py-2 rounded-lg text-white bg-primary hover:bg-primary/90 transition text-sm shadow">
							<i class="fas fa-phone"></i> Hubungi 112 Sekarang
						</a>
					</div>
				</div>
			</section>

		</div>
	</x-layouts.page-berita>
</div>

