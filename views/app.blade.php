<!DOCTYPE html>
<html lang="es" dir="ltr">
	@include('layout/head')
	<body>
		<div id="wrapper" style="margin-top: -20px;">
			@include('layout/header')
			<div id="page-wrapper">
				<div class="container-fluid">
					@yield('content')
				</div>
			</div>
		</div>
		@include('layout/footer')
	</body>
</html>
