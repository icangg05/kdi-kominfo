<div>
	<x-layouts.page
		:breadcrumb="[['fa-solid fa-building-columns', 'Profil Dinas', '#'], ['Profil Pimpinan', '#']]"
		:title="'Profil Pimpinan'"
		:desc="'Kepala Dinas Komunikasi dan Informatika Kota Kendari Sulawesi Tenggara Periode 2025 - Sekarang'">

		{{-- Card Title --}}
		<h2 class="text-center font-bold text-xl lg:text-3xl text-gray-800"> {{ $data->nama }} </h2>
		{{-- Card Title --}}

		{{-- Foto Pimpinan --}}
		<div class="mt-4 lg:mt-8 grid grid-cols-2 gap-2 lg:gap-4 lg:grid-cols-3 lg:grid-rows-1">
			<img
				class="aspect-[3/4] lg:aspect-9/10 object-cover rounded w-full h-full"
				src="https://mediakendari.com/wp-content/uploads/2023/03/IMG-20230320-WA0038.jpg"
				alt="img">

			<div class="grid grid-rows-2 gap-2 lg:gap-4 lg:grid-rows-1 lg:grid-cols-2 lg:contents">
				<img
					class="object-cover rounded w-full h-full"
					src="https://cdn.antaranews.com/cache/1200x800/2022/05/31/Kadis-pertanian-kendari.jpg.webp"
					alt="img">
				<img
					class="object-cover rounded w-full h-full"
					src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSlrUG6L3yM8t178XfUJKuHNHPkwYhI-uxvnQ&s"
					alt="img">
			</div>
		</div>
		{{-- Foto Pimpinan --}}

		<div class="mt-6 min-w-full lg:mt-12 prose prose-sm lg:prose prose-li:leading-normal [&_*]:text-cText">
			{!! $data->konten !!}
		</div>
		{{-- Riwayat Pimpinan --}}
	</x-layouts.page>
</div>
