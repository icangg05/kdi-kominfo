<div class="container">
	<x-section-title
		title="Berita Terbaru Diskominfo<br>Kota Kendari"
		desc="Informasi terbaru seputar program dan kegiatan Diskominfo Kota Kendari. <a href='#' class='text-primary'>Lihat lainnya</a>" />

	@php
		$bidangLayanan = [
		    [
		        'fa-solid fa-globe',
		        'Subdomain & Hosting',
		        'Penyediaan subdomain dan hosting website OPD serta pendampingan teknis pengelolaan server lokal maupun cloud.',
		    ],
		    [
		        'fa-solid fa-envelope-circle-check',
		        'Pembuatan Email Dinas',
		        'Layanan pembuatan akun email resmi bagi instansi atau perangkat daerah guna mendukung komunikasi kedinasan yang profesional dan terverifikasi.',
		    ],
		    [
		        'fa-solid fa-file-signature',
		        'Pengajuan TTE (Tanda Tangan Elektronik)',
		        'Fasilitasi pengajuan dan integrasi tanda tangan elektronik tersertifikasi untuk dokumen digital resmi pemerintah.',
		    ],
		    [
		        'fa-solid fa-globe',
		        'Subdomain & Hosting',
		        'Penyediaan subdomain dan hosting website OPD serta pendampingan teknis pengelolaan server lokal maupun cloud.',
		    ],
		    [
		        'fa-solid fa-envelope-circle-check',
		        'Pembuatan Email Dinas',
		        'Layanan pembuatan akun email resmi bagi instansi atau perangkat daerah guna mendukung komunikasi kedinasan yang profesional dan terverifikasi.',
		    ],
		    [
		        'fa-solid fa-file-signature',
		        'Pengajuan TTE (Tanda Tangan Elektronik)',
		        'Fasilitasi pengajuan dan integrasi tanda tangan elektronik tersertifikasi untuk dokumen digital resmi pemerintah.',
		    ],
		];
	@endphp
	<div class="grid grid-cols-3 gap-y-6 lg:gap-x-8">
		@foreach ($bidangLayanan as $index => $item)
			<a href="#" class="col-span-3 lg:col-span-1">
				<div class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl">
					<img class="w-full aspect-16/10 object-cover rounded-t-xl"
						src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/news-chanel-template-design-09dd867fae3163ba798dd4bc574a67c6_screen.jpg?ts=1635157235"
						alt="Card Image">
					<div class="p-4 md:p-5">
						<h3 class="text-lg font-bold text-gray-800">
							Card title
						</h3>
						<p class="mt-1 text-sm lg:text-base text-gray-500">
							Some quick example text to build on the card title and make up the bulk of the card's content.
						</p>
						<p class="mt-5 text-xs text-gray-500">
							Last updated 5 mins ago
						</p>
					</div>
				</div>
			</a>
		@endforeach
	</div>
</div>
