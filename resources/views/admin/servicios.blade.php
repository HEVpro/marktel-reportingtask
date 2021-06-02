@extends('layouts.master')
@push('head')
    <!-- Styles -->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">


@endpush

@section('actions')
    <h2>Incentivos</h2>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="alert alert-dark">
        <form method="GET" action="{{ route('incentivos') }}">
            @csrf
            <div class="form-check form-check-inline">
                <div class="form-group col-md-3">
                    <label class="form-check-label" for="fecha_inicio">Fecha inicio</label>
                    <input class="form-control text-center" type="date" id="finicio" name="fecha_inicio" @if ($inicio) value={{ $inicio }}
                    @else
                                value={{ \Carbon\Carbon::now()->startOfMonth() }}> @endif>
                </div>
                <div class="form-group col-md-3">
                    <label class="form-check-label" for="fecha_fin">Fecha fin</label>
                    <input class="form-control text-center" type="date" id="ffin" name="fecha_fin" @if ($fin) value={{ $fin }}
                    @else
                                value={{ \Carbon\Carbon::now()->endOfMonth() }}> @endif>

                </div>
                <div class="form-group col-md-2">
                    <label class="form-check-label" for="habiles">Dias habiles</label>
                    <input class="form-control text-center" type="number" id="habiles" name="habiles" @if ($habiles) value={{ $habiles }}
                    @else
                                        value=22 @endif>
                </div>
                <div class="form-group col-md-2">
                    <label class="form-check-label" for="habiles">Semanas mes</label>
                    <input class="form-control text-center" type="number" id="semanas" name="semanas" @if ($semanas) value={{ $semanas }}
                    @else
                                        value=4 @endif>
                </div>
                <div class="form-group col-md-2">
                    <label class="form-check-label" for="usuario">Usuario </label>
                    <select class="form-control text-center" name="usuario">
                        <option value="" selected="selected"></option>
                        @foreach ($nombres as $nombre){
                            <option value="{{ $nombre->usuario }}">{{ $nombre->usuario }}</option>
                            }
                        @endforeach

                    </select>
                </div>

                <button id="submitInforme" type="submit" class="btn btn-outline-primary btn-md">{{ __('Filtrar') }}
                </button>
            </div>
        </form>
    </div>
    <div class="alert alert-light text-left" role="alert">
        <h3>Usuario: <b>{{ $usuario }} </b></h3>
    </div>
    <div class="alert alert-secondary" role="alert">
        <div class="row">
            <div class="col-sm-3">
                <div class="card border-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">Entregados</div>
                    <div class="card-body text-primary text-left">
                        <h5 class="card-title">Informes entregados: {{ $entregados }}</h5>
                        <h5 class="card-title">Porcentaje:
                            {{ number_format(($entregados / $total_informes) * 100, 2, ',', '') }} %</h5>
                        <p class="card-text">Se calcula los informes entregados sobre el total de tareas que se debían
                            realizar en el mes.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card border-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">Retrasos</div>
                    <div class="card-body text-primary text-left">
                        <h5 class="card-title">Informes con retraso: {{ $retrasos }}</h5>
                        <h5 class="card-title">Porcentaje:
                            @if ($retrasos === 0)
                                0
                            @else
                                {{ number_format(($retrasos / $entregados) * 100, 2, ',', '') }} %

                            @endif
                        </h5>
                        <br>
                        <p class="card-text">Se calcula los informes con retraso dentro de la actividad del mes.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card border-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">Errores</div>
                    <div class="card-body text-primary text-left">
                        <h6 class="card-title">Errores leves: {{ $leves }}</h6>
                        <h6 class="card-title">Errores medios: {{ $medios }}</h6>
                        <h6 class="card-title">Errores graves: {{ $graves }}</h6>
                        <p class="card-text">Se calcula el total de errores del mes dentro de la actividad.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card border-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">Incentivos</div>
                    <div class="card-body text-primary text-center">
                        @if ($usuario !== 'GLOBAL')

                            @if ($leves === 9 || $medios === 3 || $graves >= 1)
                                <h2><span class="badge badge-danger">NO</span></h2>

                            @else
                                <h2><span class="badge badge-success">SI</span></h2>
                            @endif
                        @else
                            <h2><span class="badge badge-secondary">No aplican incentivos</span></h2>
                        @endif

                        <br>
                        <p class="card-text text-left">Lo máximo permitido son 9 errores leves, 3 errores medios o 1 error
                            grave.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>



@endsection
