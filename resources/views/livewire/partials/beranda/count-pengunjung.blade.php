<section class="relative bg-cover bg-center bg-no-repeat"
	style="background-image: url('{{ asset('img/bg-earth.webp') }}');">
	<div class="absolute inset-0 bg-primary/70 backdrop-brightness-90"></div>

	<div class="relative z-10 max-w-6xl mx-auto px-4 py-16">
		<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-white text-center">

			<!-- Total Pengunjung -->
			<div class="bg-white/10 p-6 rounded-2xl shadow-lg backdrop-blur-sm">
				<i class="fa-solid fa-users text-3xl mb-3 text-white/90"></i>
				<h4 class="text-3xl font-bold">
					<count-up>9755</count-up>
				</h4>
				<p class="mt-1 text-sm tracking-wide">Total Pengunjung</p>
			</div>

			<!-- Bulan Ini -->
			<div class="bg-white/10 p-6 rounded-2xl shadow-lg backdrop-blur-sm">
				<i class="fa-solid fa-calendar text-3xl mb-3 text-white/90"></i>
				<h4 class="text-3xl font-bold">
					<count-up>2130</count-up>
				</h4>
				<p class="mt-1 text-sm tracking-wide">Bulan Ini</p>
			</div>

			<!-- Minggu Ini -->
			<div class="bg-white/10 p-6 rounded-2xl shadow-lg backdrop-blur-sm">
				<i class="fa-solid fa-calendar-week text-3xl mb-3 text-white/90"></i>
				<h4 class="text-3xl font-bold">
					<count-up>430</count-up>
				</h4>
				<p class="mt-1 text-sm tracking-wide">Minggu Ini</p>
			</div>

			<!-- Hari Ini -->
			<div class="bg-white/10 p-6 rounded-2xl shadow-lg backdrop-blur-sm">
				<i class="fa-solid fa-calendar-day text-3xl mb-3 text-white/90"></i>
				<h4 class="text-3xl font-bold">
					<count-up>87</count-up>
				</h4>
				<p class="mt-1 text-sm tracking-wide">Hari Ini</p>
			</div>

		</div>
	</div>
</section>
