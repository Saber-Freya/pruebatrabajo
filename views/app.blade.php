<!DOCTYPE html>
<html lang="en" dir="ltr">
	{{-- Links de CSS--}}
	@include('layout/head')

	<body>
		<div id="wrapper" style="margin-top: -20px;">
			{{--Navegacion y Header--}}
			@include('layout/header')
			<div id="page-wrapper">
				<div class="container-fluid">
					{{--Contenedor Principal--}}
					@yield('content')
				</div>
			</div>
		</div>
		{{--Informacion de footer y Scripts--}}
		@include('layout/footer')
	</body>
</html>
