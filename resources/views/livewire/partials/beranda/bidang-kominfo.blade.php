<div class="container">
	<x-section-title
		title="Bidang Layanan"
		desc="Diskominfo Kota Kendari memiliki beberapa bidang yang bertanggung dalam mengelola layanan teknologi informasi dan komunikasi." />

	{{-- List Bidang Kominfo --}}
	@php
		$bidangLayanan = [
		    [
		        'fa-solid fa-building-columns',
		        'Sekretariat',
		        'Mengelola administrasi umum, keuangan, kepegawaian, dan koordinasi internal dinas secara efektif',
		    ],
		    [
		        'fa-solid fa-bullhorn',
		        'Bidang Informasi dan Komunikasi Publik',
		        'Mengelola informasi publik, hubungan media, serta strategi komunikasi pemerintah daerah secara efektif dan transparan',
		    ],
		    [
		        'fa-solid fa-network-wired',
		        'Bidang Penyelenggaraan e-Government',
		        'Mengembangkan dan menyelenggarakan sistem pemerintahan berbasis elektronik yang terintegrasi dan efisien',
		    ],
		    [
		        'fa-solid fa-shield-halved',
		        'Bidang Teknologi Informasi, Komunikasi, dan Persandian',
		        'Menyediakan infrastruktur TIK, menjaga keamanan sistem informasi, serta mengelola persandian dan komunikasi dinas',
		    ],
		];
	@endphp
	<div class="grid grid-cols-3 gap-x-0 gap-y-5 lg:gap-x-16 lg:gap-y-8">
		@foreach ($bidangLayanan as $index => $item)
			@php
				// Cek jika item terakhir dan total item tidak habis dibagi 3
				$isLast = $loop->last;
				$needsCenter = count($bidangLayanan) % 3 !== 0 && $loop->last;
			@endphp
			<div class="col-span-3 lg:col-span-1 text-center {{ $needsCenter ? 'lg:col-start-2' : '' }}">
				<div class="mx-auto size-16 lg:size-19 rounded-full border border-gray-300 flex items-center justify-center">
					<i class="{{ $item[0] }} text-2xl lg:text-4xl text-primary"></i>
				</div>
				<h6
					class="mx-auto max-w-xs lg:w-full mt-5 mb-2.5 lg:mb-4 font-bold text-lg lg:text-xl text-gray-800 leading-tight">
					{{ $item[1] }}</h6>
				<p class="mx-auto max-w-[340px] lg:w-full text-sm lg:text-base text-gray-500">{{ $item[2] }}
				</p>
			</div>
		@endforeach

	</div>
	{{-- List Bidang Kominfo --}}
</div>
