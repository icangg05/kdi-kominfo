<div id="hs-vertically-centered-modal-{{ $i }}"
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
					aria-label="Close" data-hs-overlay="#hs-vertically-centered-modal-{{ $i }}">
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
						<span>{{ $data['nama'] }}</span>
					</li>
					<li class="flex items-center gap-2">
						<i class="text-gray-500 fas fa-id-card w-5 h-5 text-base flex-shrink-0"></i>
						<strong class="min-w-[90px] lg:min-w-[100px]">NIP</strong>
						<span>:</span>
						<span>{{ $data['nip'] ?? '-' }}</span>
					</li>
					<li class="flex items-center gap-2">
						<i class="text-gray-500 fas fa-briefcase w-5 h-5 text-base flex-shrink-0"></i>
						<strong class="min-w-[90px] lg:min-w-[100px]">Jabatan</strong>
						<span>:</span>
						<span>{{ $data['jabatan']->nama }}</span>
					</li>
					<li class="flex items-center gap-2">
						<i class="text-gray-500 fas fa-cake-candles w-5 h-5 text-base flex-shrink-0"></i>
						<strong class="min-w-[90px] lg:min-w-[100px]">Tanggal Lahir</strong>
						<span>:</span>
						<span>
							{{ $data['tanggal_lahir'] ? \Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
						</span>
					</li>
					<li class="flex items-center gap-2">
						<i class="text-gray-500 fas fa-map-marker-alt w-5 h-5 text-base flex-shrink-0"></i>
						<strong class="min-w-[90px] lg:min-w-[100px]">Alamat</strong>
						<span>:</span>
						<span>{{ $data['alamat'] ?? '-' }}</span>
					</li>
				</ul>
			</div>

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
