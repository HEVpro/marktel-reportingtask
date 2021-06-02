@extends('layouts.master')
@push('head')
    <!-- Styles -->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">


@endpush

@section('actions')
    <h2>Gestión de tareas</h2>

    <div class="alert alert-dark">

        <form method="GET" action="{{ route('tareas.editar') }}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_informe">Id informe</label>
                    <input type="number" class="form-control" name="id_informe" id="id_informe">
                </div>
                <div class="form-group col-md-6">
                    <label for="usuario">Usuario</label>
                    <select type="text" class="form-control" name="usuario">
                        <option value="" selected="selected"></option>
                        @foreach ($nombres as $nombre){
                            <option value="{{ $nombre->usuario }}">{{ $nombre->usuario }}</option>
                            }
                        @endforeach

                    </select>
                </div>
            </div>

            <button id="submitInforme" type="submit" class="btn btn-outline-primary btn-sm ml-2">{{ __('Filtrar') }}
            </button>
        </form>

    </div>

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
    <div class="d-flex flex-row-reverse">
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#crearInforme">Crear</button>
    </div>
    <table id="datatable" class="table table-sm table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre Informe</th>
                <th scope="col">Subservicio</th>
                <th scope="col">Marca</th>
                <th scope="col">Envio cliente</th>
                <th scope="col">Frecuencia</th>
                <th scope="col">Dia</th>
                <th scope="col">Tiempo</th>
                <th scope="col">Hora límite</th>
                <th scope="col">Resp</th>
                <th scope="col">Estado</th>
                <th scope="col">Editar</th>
            </tr>

        </thead>
        <tbody>
            @foreach ($tareas as $tarea)
                <tr>
                    <th id="id-{{ $tarea->id }}">{{ $tarea->id }}</th>
                    <th id="nombre_Informe">{{ $tarea->nombre_informe . '-' . $tarea->servicio }}</th>
                    <th id="subservicio">{{ $tarea->subservicio . '-' . $tarea->sede }}</th>
                    <th id="marca">{{ $tarea->marca }}</th>
                    <th id="envio_cliente">{{ $tarea->envio_cliente }}</th>
                    <th id="frecuencia">{{ $tarea->frecuencia }}</th>
                    <th id="dia">{{ $tarea->dia }}</th>
                    <th id="dia">{{ $tarea->tiempo_completar }}</th>
                    <th id="hora-limite">{{ \Carbon\Carbon::parse($tarea->hora_limite)->format('H:i') }}</th>
                    <th id="responsable">{{ $tarea->usuario }}</th>
                    <th id="responsable">{{ $tarea->estado }}</th>
                    <th id="send" style="text-align:center"><button id="tr-{{ $tarea->id }}" type="button"
                            class="btn btn-primary edit" data-toggle="modal" data-target="#enviarInforme">
                            +</button></th>
                </tr>

            @endforeach
        </tbody>

    </table>
    <!-- Modal Editar usuario -->
    <div class="modal fade" id="enviarInforme" tabindex="-1" role="dialog" aria-labelledby="createUserCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Editar informe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="rig">
                    <form method="POST" action="{{ route('tareas.modificar') }}" id="editForm">
                        @csrf
                        <div class="form-group row">
                            <label for="informe_id"
                                class="col-md-4 col-form-label text-md-right">{{ __('Id') }}</label>

                            <div class="col-md-8">
                                <input id="informe_id" type="text"
                                    class="form-control @error('informe_id') is-invalid @enderror" name="informe_id"
                                    value="{{ old('Informe id') }}" required autocomplete="id" readonly>

                                @error('informe_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nombre"
                                class="col-md-4 col-form-label text-md-right">{{ __('Nombre informe') }}</label>

                            <div class="col-md-8">
                                <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" readonly>

                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="responsableId"
                                class="col-md-4 col-form-label text-md-right">{{ __('Responsable') }}</label>

                            <div class="col-md-8">
                                <input id="responsableId" type="text"
                                    class="form-control @error('responsableId') is-invalid @enderror" name="responsableId"
                                    required autocomplete="responsableId" readonly>

                                @error('responsableId')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tiempoCompletar"
                                class="col-md-4 col-form-label text-md-right">{{ __('Tiempo a completar') }}</label>

                            <div class="col-md-8">
                                <input id="tiempoCompletar" type="number" step="0.01"
                                    class="form-control @error('tiempoCompletar') is-invalid @enderror"
                                    name="tiempoCompletar" required autocomplete="tiempoCompletar">
                                <small id="tiempo" class="form-text text-muted">Usar la coma como separador si se
                                    escribe a
                                    mano.</small>
                                @error('tiempoCompletar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="envio"
                                class="col-md-4 col-form-label text-md-right">{{ __('Envio a cliente') }}</label>

                            <div class="col-md-8">
                                <select name="envio" class="custom-select" id="envio">
                                    <option selected>Escoger...</option>
                                    <option value="NO" selected="selected">NO</option>
                                    <option value="SI">SI</option>

                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="frecuencia"
                                class="col-md-4 col-form-label text-md-right">{{ __('Frecuencia') }}</label>

                            <div class="col-md-8">
                                <select name="frecuencia" class="custom-select" id="frecuencia">
                                    <option selected>Escoger...</option>
                                    <option value="DIARIO" selected="selected">DIARIO</option>
                                    <option value="SEMANAL">SEMANAL</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fdia"
                                class="col-md-4 col-form-label text-md-right">{{ __('Día de envio') }}</label>

                            <div class="col-md-8">
                                <select name="fdia" class="custom-select" id="fdia">
                                    <option value="LUNES">LUNES</option>
                                    <option value="MARTES">MARTES</option>
                                    <option value="MIÉRCOLES">MIÉRCOLES</option>
                                    <option value="JUEVES">JUEVES</option>
                                    <option value="VIERNES">VIERNES</option>
                                    <option value="NA">TODOS</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hora" class="col-md-4 col-form-label text-md-right">{{ __('Hora') }}</label>

                            <div class="col-md-8">
                                <input id="hora" type="time" class="form-control @error('hora') is-invalid @enderror"
                                    name="hora" required autocomplete="hora">

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fresponsable"
                                class="col-md-4 col-form-label text-md-right">{{ __('Responsable') }}</label>

                            <div class="col-md-8">
                                <select name="fresponsable" class="custom-select" id="fresponsable">
                                    @foreach ($nombres as $nombre){
                                        <option value="{{ $nombre->usuario }}">{{ $nombre->usuario }}</option>
                                        }
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="estado" class="col-md-4 col-form-label text-md-right">{{ __('Estado') }}</label>

                            <div class="col-md-8">
                                <select name="estado" class="custom-select" id="estado">

                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>

                                </select>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button id="submitInforme" type="submit" class="btn btn-primary mr-1">{{ __('Modificar') }}
                            </button>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal Crear informe-->
    <div class="modal fade" id="crearInforme" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Informe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('tareas.crear') }}" id="editForm">
                        @csrf
                        <div class="form-group row">
                            <label for="c_nombre_informe"
                                class="col-md-4 col-form-label text-md-right">{{ __('Nombre Informe') }}</label>

                            <div class="col-md-8">
                                <input id="c_nombre_informe" type="text"
                                    class="form-control @error('c_nombre_informe') is-invalid @enderror"
                                    name="c_nombre_informe" value="{{ old('c_nombre_informe') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="c_servicio"
                                class="col-md-4 col-form-label text-md-right">{{ __('Servicio') }}</label>

                            <div class="col-md-8">
                                <input id="c_servicio" type="text"
                                    class="form-control @error('c_servicio') is-invalid @enderror" name="c_servicio"
                                    value="{{ old('c_servicio') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="c_subservicio"
                                class="col-md-4 col-form-label text-md-right">{{ __('Subservicio') }}</label>

                            <div class="col-md-8">
                                <input id="c_subservicio" type="text"
                                    class="form-control @error('c_subservicio') is-invalid @enderror" name="c_subservicio"
                                    value="{{ old('c_subservicio') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="c_sede" class="col-md-4 col-form-label text-md-right">{{ __('Sede') }}</label>

                            <div class="col-md-8">
                                <input id="c_sede" type="text" class="form-control @error('c_sede') is-invalid @enderror"
                                    name="c_sede" value="{{ old('c_sede') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="c_tipo" class="col-md-4 col-form-label text-md-right">{{ __('Tipo') }}</label>

                            <div class="col-md-8">
                                <select name="c_tipo" class="custom-select" id="c_tipo">
                                    <option value="RESIDENCIAL">RESIDENCIAL</option>
                                    <option value="PYMES">PYMES</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="c_marca" class="col-md-4 col-form-label text-md-right">{{ __('Marca') }}</label>

                            <div class="col-md-8">
                                <input id="c_marca" type="text" class="form-control @error('c_marca') is-invalid @enderror"
                                    name="c_marca" value="{{ old('c_sede') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tiempoCompletar"
                                class="col-md-4 col-form-label text-md-right">{{ __('Tiempo a completar') }}</label>

                            <div class="col-md-8">
                                <input id="tiempoCompletar" type="number" step="0.01"
                                    class="form-control @error('tiempoCompletar') is-invalid @enderror"
                                    name="tiempoCompletar" required autocomplete="tiempoCompletar">
                                <small id="tiempo" class="form-text text-muted">Usar la coma como separador si se
                                    escribe a
                                    mano.</small>
                                @error('tiempoCompletar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="envio"
                                class="col-md-4 col-form-label text-md-right">{{ __('Envio a cliente') }}</label>

                            <div class="col-md-8">
                                <select name="envio" class="custom-select" id="envio">
                                    <option selected>Escoger...</option>
                                    <option value="NO" selected="selected">NO</option>
                                    <option value="SI">SI</option>

                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="frecuencia"
                                class="col-md-4 col-form-label text-md-right">{{ __('Frecuencia') }}</label>

                            <div class="col-md-8">
                                <select name="frecuencia" class="custom-select" id="frecuencia">
                                    <option selected>Escoger...</option>
                                    <option value="DIARIO" selected="selected">DIARIO</option>
                                    <option value="SEMANAL">SEMANAL</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fdia"
                                class="col-md-4 col-form-label text-md-right">{{ __('Día de envio') }}</label>

                            <div class="col-md-8">
                                <select name="fdia" class="custom-select" id="fdia">
                                    <option value="LUNES">LUNES</option>
                                    <option value="MARTES">MARTES</option>
                                    <option value="MIÉRCOLES">MIÉRCOLES</option>
                                    <option value="JUEVES">JUEVES</option>
                                    <option value="VIERNES">VIERNES</option>
                                    <option value="NA">TODOS</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hora" class="col-md-4 col-form-label text-md-right">{{ __('Hora') }}</label>

                            <div class="col-md-8">
                                <input id="hora" type="time" class="form-control @error('hora') is-invalid @enderror"
                                    name="hora" required autocomplete="hora">

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fresponsable"
                                class="col-md-4 col-form-label text-md-right">{{ __('Responsable') }}</label>

                            <div class="col-md-8">
                                <select name="fresponsable" class="custom-select" id="fresponsable">
                                    @foreach ($nombres as $nombre){
                                        <option value="{{ $nombre->usuario }}">{{ $nombre->usuario }}</option>
                                        }
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="estado" class="col-md-4 col-form-label text-md-right">{{ __('Estado') }}</label>

                            <div class="col-md-8">
                                <select name="estado" class="custom-select" id="estado">

                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>

                                </select>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Crear</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

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
                $('#informe_id').val(data[0]);
                $('#nombre').val(data[1]);
                $('#responsableId').val(data[9]);
                $('#tiempoCompletar').val(data[7]);
                $('#envio').val(data[4]);
                $('#frecuencia').val(data[5]);
                $('#fdia').val(data[6]);
                $('#hora').val(data[8]);
                $('#estado').val(data[10]);
                $('#fresponsable').val(data[9]);
            })
        })

    </script>

@endsection
