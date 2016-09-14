<div class = "col-sm-10 grupoFicha fichaMedica minimizada datosListasFicha">
    <div class="col-xs-12 page-header-sub"> <i class = "fa fa-list-alt"></i> <strong> Ficha Médica</strong> <i class="fa fa-info-circle" title="Si el comentario es más grande de un renglón poner el cursor sobre éste o precionar el boton de comentarios para verlo completo."></i></div>
    <div class="row">

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Asma Bronquial: ') !!} <span class="asma absoluta {!! $positivoAs !!}">{!! $asma !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="asma_coment formControlRay @if($cliente->asma_coment != '') scroolRay @endif"
                  title="{!! $cliente->asma_coment !!}"> {!! $cliente->asma_coment !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Ulcera Gastroduodenal: ') !!} <span class="ulsera absoluta {!! $positivoUl !!}">{!! $ulsera !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="ulsera_coment formControlRay @if($cliente->ulsera_coment != '') scroolRay @endif"
                  title="{!! $cliente->ulsera_coment !!}"> {!! $cliente->ulsera_coment !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Fiebre Reumática: ') !!} <span class="fiebre absoluta {!! $positivoFi !!}">{!! $fiebre!!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="fiebre_coment formControlRay @if($cliente->fiebre_coment!= '') scroolRay @endif"
                  title="{!! $cliente->fiebre_coment !!}"> {!! $cliente->fiebre_coment !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Diabetes: ') !!} <span class="diabetes absoluta {!! $positivoDi !!}">{!! $diabetes !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="diabetes_coment formControlRay @if($cliente->diabetes_coment != '') scroolRay @endif"
                  title="{!! $cliente->diabetes_coment !!}"> {!! $cliente->diabetes_coment !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Enfermedades Cardiacas: ') !!} <span class="cardiacas absoluta {!! $positivoCa !!}">{!! $cardiacas !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="cardiacas_coment formControlRay @if($cliente->cardiacas_coment != '') scroolRay @endif"
                  title="{!! $cliente->cardiacas_coment !!}"> {!! $cliente->cardiacas_coment !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Convulsiones: ') !!} <span class="convulsiones absoluta {!! $positivoCo !!}">{!! $convulsiones !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="convulsiones_coment formControlRay @if($cliente->convulsiones_coment != '') scroolRay @endif"
                  title="{!! $cliente->convulsiones_coment !!}"> {!! $cliente->convulsiones_coment !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Tuberculosis: ') !!} <span class="tuberculosis absoluta {!! $positivoTu !!}">{!! $tuberculosis !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="tuberculosis_coment formControlRay @if($cliente->tuberculosis_coment != '') scroolRay @endif"
                  title="{!! $cliente->tuberculosis_coment !!}"> {!! $cliente->tuberculosis_coment !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Vértigos o Mareos: ') !!} <span class="mareos absoluta {!! $positivoMa !!}">{!! $mareos !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="mareos_coment formControlRay @if($cliente->mareos_coment != '') scroolRay @endif"
                  title="{!! $cliente->mareos_coment !!}"> {!! $cliente->mareos_coment !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Dolor de Cabeza Severo: ') !!} <span class="dolor_cabeza absoluta {!! $positivoDo !!}">{!! $dolor_cabeza !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="dolor_cabeza_coment formControlRay @if($cliente->dolor_cabeza_coment != '') scroolRay @endif"
                  title="{!! $cliente->dolor_cabeza_coment !!}"> {!! $cliente->dolor_cabeza_coment !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Problemas Emocionales: ') !!} <span class="emocionales absoluta {!! $positivoEm !!}">{!! $emocionales !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="emocionales_coment formControlRay @if($cliente->emocionales_coment != '') scroolRay @endif"
                  title="{!! $cliente->emocionales_coment !!}"> {!! $cliente->emocionales_coment !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Hernias: ') !!} <span class="hernias absoluta {!! $positivoHe !!}">{!! $hernias !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="hernias_coment formControlRay @if($cliente->hernias_coment != '') scroolRay @endif"
                  title="{!! $cliente->hernias_coment !!}"> {!! $cliente->hernias_coment  !!}</span>
        </div>

        <div class="col-md-6 col-lg-3">
            {!! Form::label('', 'Hipertensión Arterial: ') !!} <span class="arterial absoluta {!! $positivoAr !!}">{!! $arterial !!}</span>
        </div>
        <div class="col-md-6 col-lg-3">
            <span class="arterial_coment formControlRay @if($cliente->arterial_coment != '') scroolRay @endif"
                  title="{!! $cliente->arterial_coment !!}"> {!! $cliente->arterial_coment !!}</span>
        </div>

    </div>
</div>