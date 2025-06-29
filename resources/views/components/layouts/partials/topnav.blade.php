<div
	class="hidden lg:block bg-white/90 backdrop-blur-md backdrop-saturate-150 transition-all ease-out false">
	<div class="py-1.5 pb-2 container flex items-center justify-between text-gray-800 font-semibold">
		{{-- Left Section --}}
		<div class="flex items-center gap-7">
			<p class="text-xs flex items-center space-x-1">
				<i class="fa-solid fa-calendar text-gray-600"></i>
				<span class="mt-[4px]">
					{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
				</span>
			</p>
			@auth
				<a href="{{ route('dashboard.index') }}" class="text-xs flex items-center space-x-1">
					<i class="fa-solid fa-tachometer-alt text-gray-600"></i>
					<span class="mt-[4px]">
						Dashboard Page
					</span>
				</a>
			@endauth
		</div>
		{{-- Left Section --}}

		{{-- Right Section --}}
		<div class="flex items-center gap-7">
			<p class="text-xs flex items-center space-x-1">
				<i class="fa-solid fa-phone text-gray-600"></i>
				<span class="mt-[4px]">{{ $global_telp }}</span>
			</p>
			<p class="text-xs flex items-center space-x-1">
				<i class="fa-solid fa-envelope text-gray-600"></i>
				<span class="mt-[3px]">{{ $global_email }}</span>
			</p>
		</div>
		{{-- Right Section --}}
	</div>
</div>
