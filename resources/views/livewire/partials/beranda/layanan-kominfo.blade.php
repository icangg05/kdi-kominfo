<div class="container scroll-mt-24" id="layanan-kominfo">
	<x-section-title
		title="Layanan Diskominfo"
		desc="Diskominfo Kota Kendari menyediakan layanan untuk mendukung kebutuhan teknologi informasi dan komunikasi." />

	{{-- List Bidang Kominfo --}}
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
		        'fa-solid fa-phone-volume',
		        'Layanan Telpon Darurat 112',
		        'Layanan panggilan darurat 24 jam bagi masyarakat Kota Kendari untuk keadaan gawat darurat melalui nomor tunggal 112.',
		    ],
		];
	@endphp
	<div class="grid grid-cols-3 gap-x-0 gap-y-5 lg:gap-x-14 lg:gap-y-8">
		@foreach ($bidangLayanan as $index => $item)
			@php
				// Cek jika item terakhir dan total item tidak habis dibagi 3
				$isLast = $loop->last;
				$needsCenter = count($bidangLayanan) % 3 !== 0 && $loop->last;
			@endphp
			<div class="col-span-3 lg:col-span-1 flex gap-x-5 {{ $needsCenter ? 'lg:col-start-2' : '' }}">
				<i class="{{ $item[0] }} text-2xl lg:text-4xl text-primary"></i>
				<div>
					<h6
						class="max-w-xs lg:w-full mb-2.5 lg:mb-3 font-bold text-lg lg:text-xl text-gray-800 leading-tight">
						{{ $item[1] }}</h6>
					<p class="max-w-[340px] lg:w-full text-sm lg:text-base text-gray-500">
						{{ $item[2] }}
					</p>
				</div>
			</div>
		@endforeach

	</div>
	{{-- List Bidang Kominfo --}}
</div>
