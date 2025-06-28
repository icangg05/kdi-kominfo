<div>

	<!-- Slider -->
	@include('livewire.partials.beranda.hero-carousel')
	<!-- End Slider -->

	{{-- Row --}}
	<div class="grid grid-cols-1 mt-[44.5rem] lg:mt-28 lg:-translate-y-[0rem] gap-y-16 lg:gap-y-23">
		{{-- Sambutan Kadis --}}
		@include('livewire.partials.beranda.sambutan-kadis')
		{{-- Sambutan Kadis --}}

		{{-- Bidang Kominfo --}}
		@include('livewire.partials.beranda.bidang-kominfo')
		{{-- Bidang Kominfo --}}

		{{-- Section Count Pengunjung --}}
		@include('livewire.partials.beranda.count-pengunjung')
		{{-- Section Count Pengunjung --}}

		@if ($galeri->count() > 0)
			{{-- Section Galeri --}}
			@include('livewire.partials.beranda.section-galeri')
			{{-- Section Galeri --}}
		@endif

		{{-- Layanan Kominfo --}}
		@include('livewire.partials.beranda.layanan-kominfo')
		{{-- Layanan Kominfo --}}

		@if ($berita->count() > 0) 
			{{-- Berita --}}
			@include('livewire.partials.beranda.section-berita')
			{{-- Berita --}}
		@endif
	</div>
	{{-- Row --}}

</div>
