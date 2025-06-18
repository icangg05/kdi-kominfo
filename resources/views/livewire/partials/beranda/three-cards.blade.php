<div class="mt-30 lg:mt-18 grid grid-cols-12 gap-x-0 lg:gap-x-9 gap-y-5 lg:gap-y-0">
	@php
		$threeCards = [
		    [
		        'Layanan Cepat',
		        'Layanan Darurat TI',
		        'Layanan darurat untuk masalah teknologi informasi, keamanan siber, dan infrastruktur TI.',
		        '#',
		        'fa-solid fa-triangle-exclamation', // icon darurat
		    ],
		    [
		        'Edukasi Masyarakat',
		        'Pelatihan Digital',
		        'Daftar pelatihan dan workshop literasi digital untuk masyarakat, pelajar, dan pelaku usaha.',
		        '#',
		        'fa-solid fa-chalkboard-user', // icon pelatihan
		    ],
		    [
		        'Layanan Publik',
		        'Pengaduan Masyarakat',
		        'Layanan pengaduan untuk masalah teknologi informasi dan komunikasi yang dialami oleh masyarakat.',
		        '#',
		        'fa-solid fa-comments', // icon pengaduan
		    ],
		];
	@endphp

	@foreach ($threeCards as $item)
		<div class="col-span-12 lg:col-span-4">
			<div class="h-full relative flex flex-col bg-primary shadow-2xs rounded-lg text-white overflow-hidden">
				<div class="p-5 lg:p-6.5">
					<h6 class="text-[13px] font-extralight">{{ $item[0] }}</h6>
					<h5 class="text-lg font-bold">{{ $item[1] }}</h5>
					<p class="leading-snug font-light mt-3 mb-4 text-sm">{{ $item[2] }}</p>
					<a href="{{ $item[3] }}" class="group inline-flex items-center space-x-2.5 text-sm">
						<span class="font-medium font-sen">LEARN MORE</span>
						<i class="group-hover:translate-x-0.5 transition fa-solid fa-arrow-right"></i>
					</a>
				</div>
				<span class="absolute bottom-2 right-3 text-4xl lg:text-5xl text-white/15 pointer-events-none select-none">
					<i class="{{ $item[4] }}"></i>
				</span>
			</div>
		</div>
	@endforeach

</div>
