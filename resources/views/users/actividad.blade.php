@extends('layouts.master')
@push('head')
    <!-- Styles -->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">


@endpush

@section('actions')
    <h2>Actividad</h2>
    @if (Auth::user()->rol == 'admin')
        <div class="alert alert-dark">
            <form method="GET" action="{{ route('users.actividad') }}">
                @csrf


                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="form-check-label" for="fecha_inicio">Fecha inicio</label>
                        <input class="form-control text-center" type="date" id="finicio" name="fecha_inicio" @if ($inicio) value={{ $inicio }}
                            @else
                                                                                                                                                                                                                                                value={{ \Carbon\Carbon::now()->startOfMonth() }}> @endif>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-check-label" for="fecha_fin">Fecha fin</label>
                        <input class="form-control text-center" type="date" id="ffin" name="fecha_fin" @if ($fin) value={{ $fin }}
                            @else
                                                                                                                                                                                                                                        value={{ \Carbon\Carbon::now()->endOfMonth() }}> @endif>

                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-check-label" for="usuario">Usuario </label>
                        <select class="form-control text-center" name="usuario">
                            <option value="" selected="selected"></option>
                            @foreach ($nombres as $nombre){
                                <option value="{{ $nombre->usuario }}">{{ $nombre->usuario }}</option>
                                }
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="form-check-label" for="retraso">Retraso: </label>
                        <select class="form-control text-center" id="retraso" name="retraso">
                            <option value="" selected="selected"></option>
                            <option value="NO">NO</option>
                            <option value="SI">SI</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-check-label" for="incidencia">Incidencia:</label>
                        <select class="form-control text-center" id="incidencia" name="incidencia">
                            <option value="" selected="selected"></option>
                            <option value="NO">NO</option>
                            <option value="SI">SI</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-check-label" for="error">Errores:</label>
                        <select class="form-control text-center" id="error" name="error">
                            <option value="" selected="selected"></option>
                            <option value="NO">NO</option>
                            <option value="SI">SI</option>
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <button id="submitInforme" type="submit"
                            class="btn btn-outline-primary btn-md mt-4 ">{{ __('Filtrar') }}
                        </button>
                    </div>

                </div>



            </form>
        </div>
    @endif


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
    <table id="datatable" class="table table-sm table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Actividad</th>
                <th scope="col">Id</th>
                <th scope="col">Nombre Informe</th>
                <th scope="col">Responsable</th>
                <th scope="col">Fecha</th>
                <th scope="col">Retraso</th>
                <th scope="col">Incidencia</th>
                <th scope="col">Comentario</th>
                <th scope="col">Editar</th>
                @if (Auth::user()->rol == 'admin')
                    <th scope="col">Error</th>
                    <th scope="col">Procede</th>
                @endif

            </tr>

        </thead>
        <tbody>
            @foreach ($actividades as $actividad)
                <tr>
                    <th id="id">{{ $actividad->id }}</th>
                    <th id="id">{{ $actividad->informe_id }}</th>
                    <th id="nombre_Informe">{{ $actividad->informe }}</th>
                    <th id="subservicio">{{ $actividad->usuario }}</th>
                    <th id="marca">{{ $actividad->fecha }}</th>
                    <th id="envio_cliente">{{ $actividad->retraso }}</th>
                    <th id="incidencia">{{ $actividad->incidencia }}</th>
                    <th id="frecuencia">{{ $actividad->comentario_incidencia }}</th>
                    <th id="send" style="text-align:center"><button type="button" class="btn btn-secondary edit"
                            data-toggle="modal" data-target="#editarActividad">
                            +</button></th>
                    @if (Auth::user()->rol == 'admin')
                        <th id="frecuencia">{{ $actividad->procede }}</th>
                        <th id="send" style="text-align:center"><button type="button" class="btn btn-primary edit"
                                data-toggle="modal" data-target="#enviarInforme">
                                +</button></th>
                    @endif

                </tr>

            @endforeach
        </tbody>

    </table>
    <!-- Modal Editar error -->
    <div class="modal fade" id="enviarInforme" tabindex="-1" role="dialog" aria-labelledby="createUserCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Gestión de errores</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="rig">
                    <form method="POST" action="{{ route('users.errores') }}" id="editForm">
                        @csrf
                        <div class="form-group row">
                            <label for="actividadId"
                                class="col-md-4 col-form-label text-md-right">{{ __('Actividad') }}</label>

                            <div class="col-md-8">

                                <input id="actividadId" type="text"
                                    class="form-control @error('actividadId') is-invalid @enderror" name="actividadId"
                                    value="{{ old('Informe id') }}" required autocomplete="id" autofocus readonly>

                                @error('actividadId')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="error" class="col-md-4 col-form-label text-md-right">{{ __('Error') }}</label>

                            <div class="col-md-8">
                                <select name="error" class="custom-select" id="error">
                                    <option selected>Escoger...</option>
                                    <option value="NO" selected="selected">NO</option>
                                    <option value="SI">SI</option>

                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="procede"
                                class="col-md-4 col-form-label text-md-right">{{ __('Procede') }}</label>

                            <div class="col-md-8">
                                <select name="procede" class="custom-select" id="procede">
                                    <option selected>Escoger...</option>
                                    <option value="NO" selected="selected">NO</option>
                                    <option value="SI">SI</option>

                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nivel_error"
                                class="col-md-4 col-form-label text-md-right">{{ __('Nivel de error') }}</label>

                            <div class="col-md-8">
                                <select name="nivel_error" class="custom-select" id="nivel_error">
                                    <option selected>Escoger...</option>
                                    <option value="LEVE" selected="selected">LEVE</option>
                                    <option value="MEDIO">MEDIO</option>
                                    <option value="GRAVE">GRAVE</option>
                                </select>

                            </div>
                        </div>


                        <div class="modal-footer">
                            <button id="submitInforme" type="submit"
                                class="btn btn-primary mr-1">{{ __('Gestionar error') }} </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal Editar Actividad de usuario -->
    <div class="modal fade" id="editarActividad" tabindex="-1" role="dialog" aria-labelledby="createUserCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Editar Informe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="rig">
                    <form method="POST" action="{{ route('users.modificar') }}" id="modificarInforme">
                        @csrf
                        <div class="form-group row">
                            <label for="factividadId"
                                class="col-md-4 col-form-label text-md-right">{{ __('Actividad') }}</label>

                            <div class="col-md-8">

                                <input id="factividadId" type="text"
                                    class="form-control @error('factividadId') is-invalid @enderror" name="factividadId"
                                    value="{{ old('Informe id') }}" required autocomplete="id" autofocus readonly>

                                @error('factividadId')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="incidencia"
                                class="col-md-4 col-form-label text-md-right">{{ __('Incidencia') }}</label>

                            <div class="col-md-8">
                                <select name="incidencia" class="custom-select" id="incidencia">
                                    <option selected>Escoger...</option>
                                    <option value="NO" selected="selected">NO</option>
                                    <option value="SI">SI</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="comentario"
                                class="col-md-4 col-form-label text-md-right">{{ __('Comentario') }}</label>
                            <div class="col-md-8">
                                <select name="comentario_incidencia" class="custom-select" id="comentario_incidencia">
                                    <option value="Correcto" selected="selected">Correcto</option>
                                    <option value="Retraso por apoyo a compañero y solaparse informes">
                                        Retraso por apoyo a compañero y solaparse informes</option>
                                    <option value="Recepción de brutos tarde">Recepción de brutos tarde
                                    </option>
                                    <option value="Incidencia técnica con el informe">Incidencia técnica
                                        con el informe</option>
                                    <option value="Caída de la red">Caída de la red</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="submitModificar" type="submit"
                                class="btn btn-primary mr-1">{{ __('Modificar registro') }} </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('page-js-script')

    <script type="text/javascript">
        $(document).ready(function() {
            // Evitar conflictos jQuery
            $.noConflict();
            let table = $("#datatable").DataTable();

            table.on('click', '.edit', function() {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent');
                }

                let data = table.row($tr).data();
                console.log(data);

                // Gestión de errores
                $('#actividadId').val(data[0]);
                $('#informe_id').val(data[1]);
                $('#nombre').val(data[2]);

                // Modificar incidencia
                $('#factividadId').val(data[0]);
                $('#comentario').val(data[7]);
            })
        })

    </script>

@endsection
