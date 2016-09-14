@extends('app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Sección Inicio</div>
				<div class="panel-body" align="center">
					{{--<div class="col-md-6">
						<div class="col-xs-12">
							<a class="btn btn-primary" href="{!! route('sliders.index') !!}">Agregar Imagen al Slider</a>
						</div>
						<div class="col-xs-12 margentop20">
							<a href="{!! route('sliders.index') !!}">
								<img class="img-responsive" height="auto" width="auto" src="{{asset('img/admin/inicio/slider.jpg')}}" alt="Slider"/>
							</a>
						</div>
					</div>--}}
					{{--<div class="col-md-6">
						<div class="col-xs-12">
							<a class="btn btn-primary" href="{!! route('inicamisas.index') !!}">Cambiar Imagen a las Camisas</a>
						</div>
						<div class="col-xs-12 margentop20">
							<a href="{!! route('inicamisas.index') !!}">
								<img class="img-responsive" height="auto" width="auto" src="{{asset('img/admin/inicio/camisas.jpg')}}" alt="Logo"/>
							</a>
						</div>
					</div>--}}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
