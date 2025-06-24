<div>
	<x-layouts.page
		:breadcrumb="[['fa-solid fa-building-columns', 'Profil Dinas', '#'], ['Profil Pegawai', '#']]"
		:title="'Profil Pegawai'"
		:desc="'Daftar profil pegawai Dinas Komunikasi dan Informatika Kota Kendari, Sulawesi Tenggara'">

		{{-- Card Title --}}
		<h2 class="text-center font-bold text-xl lg:text-3xl text-gray-800"> Profil Pegawai </h2>
		{{-- Card Title --}}

		<div class="flex justify-center mt-6 mb-4 px-4">
			<input
				type="text"
				wire:model.live.debounce.300ms="search"
				placeholder="Cari nama atau jabatan..."
				class="w-full max-w-md rounded-lg border border-gray-300 px-4 py-2 focus:ring-primary focus:border-primary text-sm shadow-sm" />
		</div>

		{{-- Card Pegawai --}}
		<div class="mt-4 lg:mt-8 grid grid-cols-1 gap-4 lg:gap-5 lg:grid-cols-3 px-4 lg:px-0">
			@forelse ($data as $item)
				<div wire:key="{{ $item->id }}"
					class="bg-gradient-to-br from-blue-900 to-primary rounded-xl flex items-center justify-center">
					<div
						class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-xl p-6 max-w-sm w-full text-center relative">

						<!-- Tombol menu kanan atas -->
						<button class="absolute top-4 right-4 text-white/70 hover:text-white">
							<i class="fas fa-ellipsis-v"></i>
						</button>

						<!-- Foto profil -->
						<div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-white/30">
							<img src="{{ asset($item->foto ? "storage/$item->foto" : 'img/user.svg') }}" alt="Profile Photo"
								class="w-full h-full object-cover" />
						</div>

						<!-- Nama -->
						<h3 class="text-xl font-semibold text-white">{{ $item->nama }}</h3>

						<!-- Bidang -->
						<p class="text-sm text-indigo-200 mt-1">
							<i class="fas fa-network-wired text-[13px]"></i>&nbsp;
							{{ $item->jabatan->nama }}
						</p>

						<!-- Tombol -->
						<button
							class="mt-6 text-sm lg:text-base bg-white text-indigo-900 font-semibold py-2 px-6 rounded-full shadow hover:shadow-lg transition"
							aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-vertically-centered-modal"
							data-hs-overlay="#hs-vertically-centered-modal-{{ $item->id }}">
							Lihat Profil
						</button>
					</div>
					{{--  --}}
				</div>
			@empty
				<div class="col-span-full text-center text-gray-500 mt-4 lg:mt-0">
					<i class="text-lg lg:text-xl fas fa-user-slash mb-2 lg:mb-3"></i>
					<p class="text-sm lg:text-base">Tidak ada pegawai ditemukan.</p>
				</div>
			@endforelse
		</div>
		{{-- Card Pegawai --}}

		{{-- Load More --}}
		@if ($data->hasMorePages())
			<div class="flex justify-center mt-6">
				<button
					wire:click="loadMore"
					onclick="setTimeout(() => HSOverlay?.autoInit?.(), 500)"
					wire:loading.attr="disabled"
					class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-5 py-2 rounded text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-60 disabled:cursor-not-allowed">

					{{-- Spinner saat loading --}}
					<span wire:loading wire:target="loadMore">
						<i class="fas fa-spinner fa-spin text-white"></i>
					</span>

					{{-- Tulisan tetap muncul saat tidak loading --}}
					<span wire:loading.remove wire:target="loadMore">
						Load More
					</span>
				</button>
			</div>
		@endif
		{{-- Load More --}}
	</x-layouts.page>

	@foreach ($data as $item)
		<div id="hs-vertically-centered-modal-{{ $item->id }}"
			class="text-cText hs-overlay hidden size-full fixed top-0 start-0 z-[999] overflow-x-hidden overflow-y-auto pointer-events-none"
			role="dialog" tabindex="-1" aria-labelledby="hs-vertically-centered-modal-label">
			<div
				class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-56px)] flex items-center">
				<div class="w-full flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl pointer-events-auto">

					<div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
						<h3 id="hs-vertically-centered-modal-label" class="font-bold">
							Data Pegawai
						</h3>
						<button type="button"
							class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200"
							aria-label="Close" data-hs-overlay="#hs-vertically-centered-modal-{{ $item->id }}">
							<svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
								viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
								stroke-linecap="round" stroke-linejoin="round">
								<path d="M18 6 6 18"></path>
								<path d="m6 6 12 12"></path>
							</svg>
						</button>
					</div>

					<div class="p-6 space-y-3 text-sm">
						<ul class="space-y-2 text-sm">
							<li class="flex items-center gap-2">
								<i class="text-gray-500 fas fa-user w-5 h-5 text-base flex-shrink-0"></i>
								<strong class="min-w-[90px] lg:min-w-[100px]">Nama</strong>
								<span>:</span>
								<span>{{ $item['nama'] }}</span>
							</li>
							<li class="flex items-center gap-2">
								<i class="text-gray-500 fas fa-id-card w-5 h-5 text-base flex-shrink-0"></i>
								<strong class="min-w-[90px] lg:min-w-[100px]">NIP</strong>
								<span>:</span>
								<span>{{ $item['nip'] ?? '-' }}</span>
							</li>
							<li class="flex items-center gap-2">
								<i class="text-gray-500 fas fa-briefcase w-5 h-5 text-base flex-shrink-0"></i>
								<strong class="min-w-[90px] lg:min-w-[100px]">Jabatan</strong>
								<span>:</span>
								<span>{{ $item['jabatan']->nama }}</span>
							</li>
							<li class="flex items-center gap-2">
								<i class="text-gray-500 fas fa-cake-candles w-5 h-5 text-base flex-shrink-0"></i>
								<strong class="min-w-[90px] lg:min-w-[100px]">Tanggal Lahir</strong>
								<span>:</span>
								<span>
									{{ $item['tanggal_lahir'] ? \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
								</span>
							</li>
							<li class="flex items-center gap-2">
								<i class="text-gray-500 fas fa-map-marker-alt w-5 h-5 text-base flex-shrink-0"></i>
								<strong class="min-w-[90px] lg:min-w-[100px]">Alamat</strong>
								<span>:</span>
								<span>{{ $item['alamat'] ?? '-' }}</span>
							</li>
						</ul>
					</div>

					<div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t border-gray-200">
						<button type="button"
							class="py-2 px-4 text-sm font-medium rounded-lg border border-gray-200 bg-primary text-white hover:bg-primary/85"
							data-hs-overlay="#hs-vertically-centered-modal-{{ $item->id }}">
							Tutup
						</button>
					</div>
				</div>
			</div>
		</div>
	@endforeach

	@push('scripts')
		<script>
			document.addEventListener('livewire:load', () => {
				Livewire.hook('message.processed', () => {
					// Re-inisialisasi HSOverlay setelah DOM selesai dirender ulang
					setTimeout(() => {
						if (window.HSOverlay?.autoInit) {
							HSOverlay.autoInit();
						}
					}, 100); // timeout kecil untuk pastikan DOM sudah siap
				});
			});
		</script>
	@endpush
</div>
