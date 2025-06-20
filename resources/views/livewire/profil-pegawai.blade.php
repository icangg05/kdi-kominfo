<div>
	<x-layouts.page
		:breadcrumb="[['fa-solid fa-building-columns', 'Profil Dinas', '#'], ['Profil Pegawai', '#']]"
		:title="'Profil Pegawai'"
		:desc="'Daftar profil pegawai Dinas Komunikasi dan Informatika Kota Kendari, Sulawesi Tenggara'">

		{{-- Card Title --}}
		<h2 class="text-center font-bold text-xl lg:text-3xl text-gray-800"> Profil Pegawai </h2>
		{{-- Card Title --}}

		{{-- Card Pegawai --}}
		<div class="mt-4 lg:mt-8 grid grid-cols-1 gap-4 lg:gap-5 lg:grid-cols-3 px-6 lg:px-0"
			x-data="{ selected: null, showModal: false }">
			@php
				$profiles = [
				    [
				        'nama' => 'Mary Jane',
				        'jabatan' => 'Sekretaris',
				        'lokasi' => 'Bandung',
				        'foto' => 'https://randomuser.me/api/portraits/women/44.jpg',
				    ],
				    [
				        'nama' => 'John Doe',
				        'jabatan' => 'Kepala Bidang TIK',
				        'lokasi' => 'Cimahi',
				        'foto' => 'https://randomuser.me/api/portraits/men/45.jpg',
				    ],
				    [
				        'nama' => 'Siti Aminah',
				        'jabatan' => 'Kepala Bidang E-Gov',
				        'lokasi' => 'Bogor',
				        'foto' => 'https://randomuser.me/api/portraits/women/65.jpg',
				    ],
				    [
				        'nama' => 'Firman Salam, SP',
				        'jabatan' => 'Kepala Komunikasi Publik',
				        'lokasi' => 'Depok',
				        'foto' => 'https://randomuser.me/api/portraits/men/52.jpg',
				    ],
				    [
				        'nama' => 'Nina Zahra',
				        'jabatan' => 'Analis Infrastruktur',
				        'lokasi' => 'Sumedang',
				        'foto' => 'https://randomuser.me/api/portraits/women/12.jpg',
				    ],
				    [
				        'nama' => 'Budi Santosa',
				        'jabatan' => 'Admin Persandian',
				        'lokasi' => 'Tasikmalaya',
				        'foto' => 'https://randomuser.me/api/portraits/men/12.jpg',
				    ],
				];
			@endphp

			@foreach ($profiles as $i => $item)
				<div class="bg-gradient-to-br from-blue-900 to-primary rounded-xl flex items-center justify-center">
					<div
						class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-xl p-6 max-w-sm w-full text-center relative">

						<!-- Tombol menu kanan atas -->
						<button class="absolute top-4 right-4 text-white/70 hover:text-white">
							<i class="fas fa-ellipsis-v"></i>
						</button>

						<!-- Foto profil -->
						<div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-white/30">
							<img src="{{ $item['foto'] }}" alt="Profile Photo"
								class="w-full h-full object-cover" />
						</div>

						<!-- Nama -->
						<h3 class="text-xl font-semibold text-white">{{ $item['nama'] }}</h3>

						<!-- Bidang -->
						<p class="text-sm text-indigo-200 mt-1 flex items-center justify-center gap-2">
							<i class="fas fa-network-wired text-[13px] lg:text-sm"></i> {{ $item['jabatan'] }}
						</p>

						<!-- Tombol -->
						<button
							class="mt-6 text-sm lg:text-base bg-white text-indigo-900 font-semibold py-2 px-6 rounded-full shadow hover:shadow-lg transition"
							aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-vertically-centered-modal"
							data-hs-overlay="#hs-vertically-centered-modal-{{ $i }}">
							Lihat Profil
						</button>
					</div>
					{{--  --}}
				</div>

				{{-- Modal --}}
				@push('modal')
					<div id="hs-vertically-centered-modal-{{ $i }}"
						class="hs-overlay hidden size-full fixed top-0 start-0 z-[999] overflow-x-hidden overflow-y-auto pointer-events-none"
						role="dialog" tabindex="-1" aria-labelledby="hs-vertically-centered-modal-label">
						<div
							class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-56px)] flex items-center">
							<div class="w-full flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl pointer-events-auto">

								{{-- Header --}}
								<div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
									<h3 id="hs-vertically-centered-modal-label" class="font-bold text-gray-800">
										Data Pegawai
									</h3>
									<button type="button"
										class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200"
										aria-label="Close" data-hs-overlay="#hs-vertically-centered-modal-{{ $i }}">
										<svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
											viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
											stroke-linecap="round" stroke-linejoin="round">
											<path d="M18 6 6 18"></path>
											<path d="m6 6 12 12"></path>
										</svg>
									</button>
								</div>

								{{-- Konten --}}
								<div class="p-6 space-y-3 text-sm text-gray-700">
									<div class="flex items-start gap-3">
										<i class="fas fa-user text-gray-500 mt-1 w-5"></i>
										<span class="w-28">Nama</span>
										<div class="flex-1">: <span class="font-semibold">{{ $item['nama'] }}</span></div>
									</div>
									<div class="flex items-start gap-3">
										<i class="fas fa-id-card text-gray-500 mt-1 w-5"></i>
										<span class="w-28">NIP</span>
										<div class="flex-1">: <span class="font-semibold">{{ $item['nip'] ?? '-' }}</span></div>
									</div>
									<div class="flex items-start gap-3">
										<i class="fas fa-briefcase text-gray-500 mt-1 w-5"></i>
										<span class="w-28">Jabatan</span>
										<div class="flex-1">: <span class="font-semibold">{{ $item['jabatan'] }}</span></div>
									</div>
									<div class="flex items-start gap-3">
										<i class="fas fa-cake-candles text-gray-500 mt-1 w-5"></i>
										<span class="w-28">Tanggal Lahir</span>
										<div class="flex-1">: <span class="font-semibold">{{ $item['tgl_lahir'] ?? '-' }}</span></div>
									</div>
									<div class="flex items-start gap-3">
										<i class="fas fa-map-marker-alt text-gray-500 mt-1 w-5"></i>
										<span class="w-28">Alamat</span>
										<div class="flex-1">: <span class="font-semibold">{{ $item['alamat'] ?? '-' }}</span></div>
									</div>
								</div>

								{{-- Footer --}}
								<div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t border-gray-200">
									<button type="button"
										class="py-2 px-4 text-sm font-medium rounded-lg border border-gray-200 bg-primary text-white hover:bg-primary/85"
										data-hs-overlay="#hs-vertically-centered-modal-{{ $i }}">
										Tutup
									</button>
								</div>
							</div>
						</div>
					</div>
				@endpush
			@endforeach
		</div>
		{{-- Card Pegawai --}}
	</x-layouts.page>
</div>
