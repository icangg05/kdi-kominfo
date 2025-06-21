<div>
	<x-layouts.page
		:breadcrumb="[['fa-solid fa-building-columns', 'Profil Dinas', '#'], ['Struktur Organisasi', '#']]"
		:title="'Struktur Organisasi'"
		:desc="'Dirancang untuk mendukung efektivitas dan efisiensi dalam pelaksanaan tugas dan fungsinya untuk mempercepat transformasi digital.'">

		{{-- Card Title --}}
		<h2 class="text-center font-bold text-xl lg:text-3xl text-gray-800"> Struktur Organisasi </h2>
		{{-- Card Title --}}

		{{-- Gambar Struktur --}}
		<div class="mt-6 min-w-full lg:mt-12 prose prose-sm lg:prose prose-li:leading-normal lg:prose-li:leading-tight">
			<img class="w-full rounded c2Shadow"
				src="https://mgmall.s3.amazonaws.com/img/012025/838b75648fbdfa78a60899f859de7247d1e6044b.jpg" alt="image">
		</div>
		{{-- Gambar Struktur --}}

		{{-- Card Bidang Kominfo --}}
		@php
			$bidangLayanan = [
			    [
			        'icon' => 'fas fa-building-columns',
			        'title' => 'Sekretariat',
			        'desc' => 'Mengelola administrasi umum, keuangan, kepegawaian, dan koordinasi internal dinas secara efektif',
			        'color' => 'text-blue-600',
			    ],
			    [
			        'icon' => 'fas fa-bullhorn',
			        'title' => 'Bidang Informasi dan Komunikasi Publik',
			        'desc' =>
			            'Mengelola informasi publik, hubungan media, serta strategi komunikasi pemerintah daerah secara efektif dan transparan',
			        'color' => 'text-yellow-500',
			    ],
			    [
			        'icon' => 'fas fa-network-wired',
			        'title' => 'Bidang Penyelenggaraan e-Government',
			        'desc' =>
			            'Mengembangkan dan menyelenggarakan sistem pemerintahan berbasis elektronik yang terintegrasi dan efisien',
			        'color' => 'text-indigo-600',
			    ],
			    [
			        'icon' => 'fas fa-shield-halved',
			        'title' => 'Bidang Teknologi Informasi, Komunikasi, dan Persandian',
			        'desc' =>
			            'Menyediakan infrastruktur TIK, menjaga keamanan sistem informasi, serta mengelola persandian dan komunikasi dinas',
			        'color' => 'text-emerald-600',
			    ],
			];
		@endphp

		<div class="mt-12 px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl">
			@foreach ($bidangLayanan as $item)
				<div
					class="bg-white rounded-2xl shadow-md cShadow transition-shadow duration-300 p-6 relative overflow-hidden group">
					<div class="{{ $item['color'] }} text-3xl mb-4">
						<i class="{{ $item['icon'] }}"></i>
					</div>
					<h3 class="text-lg font-semibold leading-[24px] text-gray-900 mb-2"> {{ $item['title'] }} </h3>
					<p class="text-gray-600 text-sm leading-relaxed">
						{{ $item['desc'] }}
					</p>
					<div
						class="absolute bottom-4 right-4 opacity-10 text-6xl text-gray-300 group-hover:scale-110 transition-transform duration-300">
						<i class="fas fa-circle-notch"></i>
					</div>
				</div>
			@endforeach
		</div>

		{{-- Card Bidang Kominfo --}}
	</x-layouts.page>
</div>
