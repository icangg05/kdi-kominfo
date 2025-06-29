@php
	$kategoriDokumen = App\Models\KategoriDokumen::all();
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
					<form action="{{ route('dokumen.index') }}" class="relative">
						<input name="search" type="text" placeholder="Cari dokumen..."
							class="w-full px-4 py-2 pl-10 text-sm border rounded-lg border-gray-300 focus:border-primary focus:ring-0"
							autocomplete="off">
						<div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
							<i class="fas fa-search"></i>
						</div>
					</form>
				</div>

				@if (request('search'))
					<div class="block lg:hidden bg-white/70 backdrop-blur-md rounded-xl p-4 c2Shadow">
						<p class="text-sm text-gray-500 mb-1 font-medium uppercase tracking-wide">Kata Kunci Pencarian</p>
						<div class="text-primary text-sm lg:text-base font-semibold italic">
							<i class="fas fa-magnifying-glass mr-1 text-primary/70"></i> {{ request('search') }}
						</div>
					</div>
				@endif

				<!-- Kategori Dokumen -->
				<x-card-kategori-dokumen />
			</div>

			<!-- Daftar Dokumen -->
			<div class="md:col-span-3 space-y-5">
				@forelse ($data as $dokumen)
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
								<i class="fas fa-folder-open mr-1"></i> {{ $dokumen->kategori_nama }}
							</span>
							<h3 class="text-lg font-semibold text-gray-800 line-clamp-1">{{ $dokumen->judul }}</h3>
							<p class="text-sm text-cText line-clamp-2">{{ $dokumen->deskripsi }}</p>

							<!-- Meta Info -->
							<div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-2">
								<div class="flex items-center gap-1">
									<i class="fas fa-download text-gray-400"></i> {{ $dokumen->total_unduhan }} Unduhan
								</div>
								<div class="flex items-center gap-1">
									<i class="fas fa-calendar-alt text-gray-400"></i>
									{{ \Carbon\Carbon::parse($dokumen->created_at)->translatedFormat('l, d M Y') }}
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
							@php
								$fileExists = $dokumen->file && Storage::disk('public')->exists($dokumen->file);
							@endphp

							<a href="{{ $fileExists ? route('download', $dokumen->id) : '#' }}"
								class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm transition bg-primary text-white 
									{{ $fileExists ? 'hover:bg-primary/90' : 'pointer-events-none opacity-50' }}">
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
									<div class="p-6 space-y-3 text-sm text-cText">
										<p class="text-[15px] lg:text-base leading-snug">{{ $dokumen->deskripsi }}</p>

										<ul class="space-y-2 text-sm">
											@php
												$detailItems = [
												    [
												        'icon' => 'fas fa-layer-group',
												        'label' => 'Kategori',
												        'value' => $dokumen->kategori_nama,
												    ],
												    [
												        'icon' => 'fas fa-calendar-alt',
												        'label' => 'Tanggal',
												        'value' => \Carbon\Carbon::parse($dokumen->created_at)->translatedFormat('d F Y'),
												    ],
												    [
												        'icon' => 'fas fa-download',
												        'label' => 'Unduhan',
												        'value' => $dokumen->total_unduhan,
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
										<a href="{{ $fileExists ? route('download', $dokumen->id) : '#' }}"
											class="inline-flex items-center gap-2 text-sm font-medium bg-primary text-white py-2 px-4 rounded-lg
											{{ $fileExists ? 'hover:bg-primary/90' : 'pointer-events-none opacity-50' }}"">
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
					<x-empty-card message="Tidak ada dokumen yang tersedia." />
				@endforelse

				{{-- Pagination --}}
				<div class="mt-2">
					{{ $data->links() }}
				</div>
			</div>
		</div>
	</x-layouts.page-berita>
</div>
