@php
	$kategoriDokumen = ['Dokumen Evaluasi', 'Dokumen Perencanaan', 'Produk Hukum'];

	$dokumenList = collect([
	    (object) [
	        'id' => 1,
	        'judul' => 'LKIP 2024',
	        'deskripsi' => 'Laporan Kinerja Instansi Pemerintah (LKIP) Tahun Anggaran 2024',
	        'kategori' => 'Dokumen Evaluasi',
	        'jumlah_unduh' => 20,
	        'tanggal' => '2025-05-19',
	        'url' => '#',
	    ],
	    (object) [
	        'id' => 2,
	        'judul' => 'Rencana Kerja 2025',
	        'deskripsi' => 'Rencana kerja tahunan Diskominfo Kendari untuk tahun 2025.',
	        'kategori' => 'Dokumen Perencanaan',
	        'jumlah_unduh' => 12,
	        'tanggal' => '2025-03-01',
	        'url' => '#',
	    ],
	    (object) [
	        'id' => 3,
	        'judul' => 'Peraturan Wali Kota No. 5',
	        'deskripsi' => 'Peraturan Wali Kota Kendari tentang pengelolaan data dan informasi.',
	        'kategori' => 'Produk Hukum',
	        'jumlah_unduh' => 33,
	        'tanggal' => '2024-11-15',
	        'url' => '#',
	    ],
	    (object) [
	        'id' => 4,
	        'judul' => 'Evaluasi Program Digitalisasi',
	        'deskripsi' => 'Laporan evaluasi terhadap program digitalisasi pelayanan publik.',
	        'kategori' => 'Dokumen Evaluasi',
	        'jumlah_unduh' => 15,
	        'tanggal' => '2024-08-09',
	        'url' => '#',
	    ],
	]);

	$kategoriAktif = request('kategori');
	if ($kategoriAktif) {
	    $dokumenList = $dokumenList->where('kategori', $kategoriAktif);
	}
@endphp


<div>
	<x-layouts.page-berita
		:breadcrumb="[['Dokumen', '#']]"
		:title="'Dokumen Publikasi'"
		:desc="'Kumpulan dokumen resmi dari Dinas Komunikasi dan Informatika Kota Kendari yang diklasifikasikan berdasarkan kategori, tahun, dan jenis dokumen'">

		<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
			<!-- Sidebar Filter Kategori -->
			<div class="space-y-6 md:sticky md:top-27 h-fit">
				<!-- Pencarian -->
				<div class="bg-white rounded-xl c2Shadow p-4">
					<h4 class="text-sm font-semibold text-primary mb-2">Pencarian</h4>
					<div class="relative">
						<input type="text" placeholder="Cari dokumen..."
							class="w-full px-4 py-2 pl-10 text-sm border rounded-lg border-gray-300 focus:border-primary focus:ring-0"
							autocomplete="off">
						<div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
							<i class="fas fa-search"></i>
						</div>
					</div>
				</div>

				<!-- Kategori Dokumen -->
				<div class="bg-white rounded-xl c2Shadow p-4">
					<h4 class="text-sm font-semibold text-gray-700 mb-3">Kategori Dokumen</h4>
					<ul class="space-y-2 text-sm text-gray-700">
						<li>
							<a href="{{ route('dokumen.index') }}"
								class="flex items-center gap-2 {{ request('kategori') === null ? 'text-primary font-medium' : 'hover:text-primary' }}">
								<i class="fas fa-folder text-primary"></i> Semua Dokumen
							</a>
						</li>
						@foreach ($kategoriDokumen as $kategori)
							<li>
								<a href="{{ route('dokumen.index', ['kategori' => $kategori]) }}"
									class="flex items-center gap-2 {{ request('kategori') === $kategori ? 'text-primary font-medium' : 'hover:text-primary' }}">
									<i class="fas fa-folder text-primary"></i> {{ $kategori }}
								</a>
							</li>
						@endforeach
					</ul>
				</div>
			</div>

			<!-- Daftar Dokumen -->
			<div class="md:col-span-3 space-y-5">
				@forelse ($dokumenList as $dokumen)
					<div
						class="bg-white rounded-xl cShadow c2Shadow p-5 flex flex-col md:flex-row md:items-center gap-4 md:gap-6 transition-shadow">
						<!-- Icon Dokumen -->
						<div class="flex-shrink-0">
							<div class="size-19 rounded-xl bg-gray-100 flex items-center justify-center text-red-500 text-3xl">
								<i class="fas fa-file-pdf"></i>
							</div>
						</div>

						<!-- Informasi Utama -->
						<div class="flex-1 space-y-1">
							<span class="inline-block text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full font-medium">
								<i class="fas fa-folder-open mr-1"></i> {{ $dokumen->kategori }}
							</span>
							<h3 class="text-lg font-semibold text-gray-800">{{ $dokumen->judul }}</h3>
							<p class="text-sm text-gray-600">{{ $dokumen->deskripsi }}</p>

							<!-- Meta Info -->
							<div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-2">
								<div class="flex items-center gap-1">
									<i class="fas fa-download text-gray-400"></i> {{ $dokumen->jumlah_unduh }} Unduhan
								</div>
								<div class="flex items-center gap-1">
									<i class="fas fa-calendar-alt text-gray-400"></i>
									{{ \Carbon\Carbon::parse($dokumen->tanggal)->translatedFormat('l, d M Y') }}
								</div>
							</div>
						</div>

						<!-- Aksi -->
						<div class="flex flex-col sm:flex-row gap-2">
							<a href="javascript:void(0)"
								class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-primary text-primary rounded-lg text-sm hover:bg-primary hover:text-white transition"
								data-hs-overlay="#modal-dokumen-{{ $dokumen->id }}">
								<i class="fas fa-info-circle"></i> Detail
							</a>
							<a href="{{ $dokumen->url }}"
								class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90 transition">
								<i class="fas fa-cloud-download-alt"></i> Unduh
							</a>
						</div>
					</div>

					{{-- Modal --}}
					<!-- Modal Detail Dokumen -->
					@push('modal')
						<div id="modal-dokumen-{{ $dokumen->id }}"
							class="hs-overlay hidden size-full fixed top-0 start-0 z-[999] overflow-x-hidden overflow-y-auto pointer-events-none"
							role="dialog" aria-labelledby="modal-dokumen-title-{{ $dokumen->id }}" tabindex="-1">
							<div
								class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-56px)] flex items-center">
								<div class="w-full bg-white border border-gray-200 shadow-lg rounded-xl pointer-events-auto">
									<!-- Header -->
									<div class="flex justify-between items-center px-4 py-3 border-b border-gray-200">
										<h3 id="modal-dokumen-title-{{ $dokumen->id }}" class="text-lg font-bold text-gray-800">
											<i class="fas fa-file-alt text-primary mr-2"></i> {{ $dokumen->judul }}
										</h3>
										<button type="button"
											class="size-8 inline-flex justify-center items-center rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200"
											data-hs-overlay="#modal-dokumen-{{ $dokumen->id }}">
											<i class="fas fa-times"></i>
										</button>
									</div>

									<!-- Body -->
									<div class="p-6 space-y-3 text-sm text-gray-700">
										<p class="text-[15px] lg:text-base text-gray-800">{{ $dokumen->deskripsi }}</p>

										<ul class="space-y-2 text-sm text-gray-700">
											@php
												$detailItems = [
												    [
												        'icon' => 'fas fa-layer-group',
												        'label' => 'Kategori',
												        'value' => $dokumen->kategori,
												    ],
												    [
												        'icon' => 'fas fa-calendar-alt',
												        'label' => 'Tanggal',
												        'value' => \Carbon\Carbon::parse($dokumen->tanggal)->translatedFormat('d F Y'),
												    ],
												    [
												        'icon' => 'fas fa-download',
												        'label' => 'Unduhan',
												        'value' => $dokumen->jumlah_unduh,
												    ],
												];
											@endphp

											@foreach ($detailItems as $item)
												<li class="flex items-center gap-2">
													<i class="{{ $item['icon'] }} text-gray-400 w-5 h-5 text-base flex-shrink-0"></i>
													<strong class="min-w-[90px]">{{ $item['label'] }}</strong>
													<span class="text-gray-600">:</span>
													<span class="text-gray-800">{{ $item['value'] }}</span>
												</li>
											@endforeach
										</ul>
									</div>

									<!-- Footer -->
									<div class="flex justify-between items-center px-4 py-3 border-t border-gray-200">
										<a href="{{ $dokumen->url }}"
											class="inline-flex items-center gap-2 text-sm font-medium bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/90">
											<i class="fas fa-cloud-download-alt"></i> Unduh Dokumen
										</a>
										<button type="button" class="text-sm px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100"
											data-hs-overlay="#modal-dokumen-{{ $dokumen->id }}">
											Tutup
										</button>
									</div>
								</div>
							</div>
						</div>
					@endpush

				@empty
					<div class="bg-white rounded-xl cShadow c2Shadow p-5 transition-shadow">
						<p class="text-center text-gray-500">Tidak ada dokumen yang tersedia.</p>
					</div>
				@endforelse
			</div>
		</div>
	</x-layouts.page-berita>
</div>
