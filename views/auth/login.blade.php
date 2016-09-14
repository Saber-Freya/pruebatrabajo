<!DOCTYPE html>
<html lang = "es">
{{-- Links de CSS y JS--}}
@include('layout/head')
<body>

<div class = "container">
	<div class = "row margentop100">
		<div class = "col-md-4 col-md-offset-4">
			<div class = "login-panel panel panel-default">
				<div class = "panel-heading">
					<a href = "{{ url('/') }}">
						<img class = "img-responsive" height = "auto" width = "100%" src = "{{asset('img/logo/logo.png')}}"
							 alt = "logo"/>
					</a>
				</div>
				<div class = "panel-body">
					@include('common.errors')
					<form role = "form" method = "POST" action = "{{ url('/auth/login') }}">
						<input type = "hidden" name = "_token" value = "{{ csrf_token() }}">
						<fieldset>
							<div class = "form-group">
								<input class = "form-control" placeholder = "Usuario" name = "name" type = "text"
									   autofocus>
							</div>
							<div class = "form-group">
								<input class = "form-control" placeholder = "Password" name = "password"
									   type = "password" value = "">
							</div>
							<div class = "form-group">
								<div class = "col-md-6 col-md-push-3">
									<button type = "submit" class = "btn btn-success btn-block">Iniciar Sesion</button>
								</div>
							</div>
						</fieldset>
					</form>
					<div class="col-xs-12">
						<div class="mejor-firefox">Para un desempe&ntilde;o optimo del sistema, usar el explorador de internet <a href="{!! url('https://www.mozilla.org/en-US/firefox/new/?scene=2#download-fx') !!}">firefox</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{--Informacion de footer y Scripts--}}
@include('layout/footer')
</body>
</html>