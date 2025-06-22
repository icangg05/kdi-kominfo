<div>
	<x-layouts.page
		:breadcrumb="[['fa-solid fa-building-columns', 'Profil Dinas', '#'], ['Tupoksi', '#']]"
		:title="'Tugas Pokok dan Fungsi'"
		:desc="'Diskominfo Kota Kendari menjalankan peran vital dalam pengelolaan informasi, komunikasi publik, statistik, dan persandian, demi terciptanya tata kelola pemerintahan yang transparan dan inovatif.'">

		{{-- Hero dengan Ilustrasi --}}
		<div class="flex flex-col-reverse lg:flex-row items-center gap-8">
			<div class="lg:w-1/2 text-gray-700">
				<h2 class="text-xl lg:text-3xl font-bold text-primary mb-4">Tugas Pokok</h2>
				<p class="text-sm lg:text-base leading-relaxed">
					Dinas Komunikasi dan Informatika Kota Kendari memiliki tugas menyelenggarakan urusan pemerintahan daerah dalam
					bidang komunikasi, informatika, statistik, dan persandian berdasarkan asas otonomi dan tugas pembantuan.
				</p>
			</div>
			<div class="lg:w-1/2">
				<img src="{{ asset('img/tupoksi.svg') }}" alt="Ilustrasi Tupoksi" class="w-full max-w-md mx-auto">
			</div>
		</div>

		{{-- Fungsi Diskominfo --}}
		<div class="mt-16">
			<div class="text-center mb-10">
				<h2 class="text-xl lg:text-3xl font-bold text-primary">Fungsi Diskominfo</h2>
				<p class="text-sm text-gray-500 mt-2 max-w-xl mx-auto">
					Fungsi-fungsi berikut menjadi dasar dalam pelaksanaan tugas pelayanan publik dan pengelolaan teknologi informasi di
					Kota Kendari.
				</p>
			</div>

			@php
				$fungsi = [
				    [
				        'icon' => 'fa-solid fa-lightbulb',
				        'text' => 'Perumusan kebijakan teknis di bidang komunikasi, informatika, statistik, dan persandian.',
				    ],
				    [
				        'icon' => 'fa-solid fa-gears',
				        'text' => 'Pelaksanaan kebijakan dalam rangka penyelenggaraan urusan pemerintahan bidang tersebut.',
				    ],
				    [
				        'icon' => 'fa-solid fa-folder-open',
				        'text' => 'Penyelenggaraan administrasi dan manajemen dinas yang efisien dan akuntabel.',
				    ],
				    [
				        'icon' => 'fa-solid fa-network-wired',
				        'text' => 'Pembinaan dan pengawasan terhadap pelaksanaan tugas bidang komunikasi dan informatika.',
				    ],
				    [
				        'icon' => 'fa-solid fa-clipboard-check',
				        'text' => 'Pelaksanaan tugas tambahan dari Wali Kota sesuai fungsi dan peraturan perundangan.',
				    ],
				];
			@endphp

			<div class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto">
				@foreach ($fungsi as $item)
					<div class="bg-white shadow-md p-6 rounded-xl border border-gray-100 flex gap-4 items-start">
						<div class="text-primary text-3xl">
							<i class="{{ $item['icon'] }}"></i>
						</div>
						<p class="text-gray-700 text-sm lg:text-base leading-relaxed">{{ $item['text'] }}</p>
					</div>
				@endforeach
			</div>
		</div>

	</x-layouts.page>
</div>
