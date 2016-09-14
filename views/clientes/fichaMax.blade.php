<div class = "col-sm-10 grupoFicha fichaMedica maximizada datosListasFicha datosComentarios hidden">
    <div class="col-xs-12 page-header-sub"> <i class = "fa fa-list-alt"></i> <strong> Ficha Médica</strong></div>
    <div class="row">

        <div class="col-sm-6 @if($cliente->asma_coment == '') hidden @endif">
            {!! Form::label('', 'Asma Bronquial: ') !!} <span class="asma {!! $positivoAs !!}">{!! $asma !!} </span>
            <span class="asma_coment" title="{!! $cliente->asma_coment !!}">{!! $cliente->asma_coment !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->ulsera_coment == '') hidden @endif">
            {!! Form::label('', 'Ulcera Gastroduodenal: ') !!} <span class="ulsera {!! $positivoUl !!}">{!! $ulsera !!} </span>
            <span class="ulsera_coment" title="{!! $cliente->ulsera_coment !!}">{!! $cliente->ulsera_coment !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->fiebre_coment == '') hidden @endif">
            {!! Form::label('', 'Fiebre Reumática: ') !!} <span class="fiebre {!! $positivoFi !!}">{!! $fiebre!!} </span>
            <span class="fiebre_coment" title="{!! $cliente->fiebre_coment !!}">{!! $cliente->fiebre_coment !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->diabetes_coment == '') hidden @endif">
            {!! Form::label('', 'Diabetes: ') !!} <span class="diabetes {!! $positivoDi !!}">{!! $diabetes !!} </span>
            <span class="diabetes_coment" title="{!! $cliente->diabetes_coment !!}">{!! $cliente->diabetes_coment !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->cardiacas_coment == '') hidden @endif">
            {!! Form::label('', 'Enfermedades Cardiacas: ') !!} <span class="cardiacas {!! $positivoCa !!}">{!! $cardiacas !!} </span>
            <span class="cardiacas_coment" title="{!! $cliente->cardiacas_coment !!}">{!! $cliente->cardiacas_coment !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->convulsiones_coment == '') hidden @endif">
            {!! Form::label('', 'Convulsiones: ') !!} <span class="convulsiones {!! $positivoCo !!}">{!! $convulsiones !!} </span>
            <span class="convulsiones_coment" title="{!! $cliente->convulsiones_coment !!}">{!! $cliente->convulsiones_coment !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->tuberculosis_coment == '') hidden @endif">
            {!! Form::label('', 'Tuberculosis: ') !!} <span class="tuberculosis {!! $positivoTu !!}">{!! $tuberculosis !!} </span>
            <span class="tuberculosis_coment" title="{!! $cliente->tuberculosis_coment !!}">{!! $cliente->tuberculosis_coment !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->mareos_coment == '') hidden @endif">
            {!! Form::label('', 'Vértigos o Mareos: ') !!} <span class="mareos {!! $positivoMa !!}">{!! $mareos !!} </span>
            <span class="mareos_coment" title="{!! $cliente->mareos_coment !!}">{!! $cliente->mareos_coment !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->dolor_cabeza_coment == '') hidden @endif">
            {!! Form::label('', 'Dolor de Cabeza Severo: ') !!} <span class="dolor_cabeza {!! $positivoDo !!}">{!! $dolor_cabeza !!} </span>
            <span class="dolor_cabeza_coment" title="{!! $cliente->dolor_cabeza_coment !!}">{!! $cliente->dolor_cabeza_coment !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->emocionales_coment == '') hidden @endif">
            {!! Form::label('', 'Problemas Emocionales: ') !!} <span class="emocionales {!! $positivoEm !!}">{!! $emocionales !!} </span>
            <span class="emocionales_coment" title="{!! $cliente->emocionales_coment !!}">{!! $cliente->emocionales_coment !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->hernias_coment == '') hidden @endif">
            {!! Form::label('', 'Hernias: ') !!} <span class="hernias {!! $positivoHe !!}">{!! $hernias !!} </span>
            <span class="hernias_coment" title="{!! $cliente->hernias_coment !!}">{!! $cliente->hernias_coment  !!}</span>
        </div>

        <div class="col-sm-6 @if($cliente->arterial_coment == '') hidden @endif">
            {!! Form::label('', 'Hipertensión Arterial: ') !!} <span class="arterial {!! $positivoAr !!}">{!! $arterial !!} </span>
            <span class="arterial_coment" title="{!! $cliente->arterial_coment !!}">{!! $cliente->arterial_coment !!}</span>
        </div>

    </div>
</div>