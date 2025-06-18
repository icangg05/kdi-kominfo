<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	{{-- Load Style --}}
	@include('components.layouts.partials.head')
	{{-- Load Style --}}
</head>

<body class="font-public-sans antialiased">
	{{-- Navbar --}}
	<x-layouts.partials.navbar />
	{{-- Navbar --}}

	{{-- Dynamis Content --}}
	{{ $slot }}
	{{-- Dynamis Content --}}

	{{-- Footer --}}
	<x-layouts.partials.footer />
	{{-- Footer --}}


	{{-- Load Scripts --}}
	@include('components.layouts.partials.script')
	{{-- Load Scripts --}}
</body>

</html>
