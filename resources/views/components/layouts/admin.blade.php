<!DOCTYPE html>
<html lang="en">

<head>
	@include('components.partials-admin.head')
</head>

<body class="app sidebar-mini rtl">
	<!-- Navbar-->
	@include('components.partials-admin.header')
	<!-- Sidebar menu-->
	@include('components.partials-admin.sidebar')
	
	<main class="app-content">
		{{ $slot }}
	</main>

	<!-- Essential javascripts for application to work-->
	@include('components.partials-admin.script')
	
</body>

</html>
