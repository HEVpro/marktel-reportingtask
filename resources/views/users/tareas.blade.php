@extends('layouts.master')
@push('head')
    <!-- Styles -->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">


@endpush

@section('actions')
    <h2>Tareas</h2>
    <div class="alert alert-dark">
        <h5>Informes: <b>{{ $reports }} </b> Enviados: <b>{{ $enviados }} </b> Completado:
            @if ($reports === 0)
                <b>0</b>


            @else
                <b>{{ number_format(($enviados / $reports) * 100, 2, ',', '') }}
                    %</b>
            @endif
        </h5>
    </div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    @if (session('enviado'))
        <div class="alert alert-danger">
            {{ session('enviado') }}
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
        <form method="GET" action="{{ route('users.tareas') }}">
            @csrf
            <input name="diarios" value="SI" type="hidden">
            <button type="submit" class="btn btn-secondary">Diarios</button>
        </form>
        <form method="GET" action="{{ route('users.tareas') }}">
            @csrf
            <input name="todos" value="SI" type="hidden">
            <button type="submit" class="btn btn-primary mr-2">Todos</button>
        </form>
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
                <th scope="col">Hora límite</th>
                <th scope="col">Resp. Id</th>
                <th scope="col">Enviar</th>
            </tr>

        </thead>
        <tbody>
            @foreach ($tareas as $tarea)
                <tr class="
                                                                                                <?php foreach ($marcados as $marcado) {
                                                                                                    if ($marcado->informe_id === $tarea->id) {
                                                                                                        echo 'table-success';
                                                                                                    }
                                                                                                } ?>
                                                                                            ">
                    <th id="id-{{ $tarea->id }}">{{ $tarea->id }}</th>
                    <th id="nombre_Informe">{{ $tarea->nombre_informe . '-' . $tarea->servicio }}</th>
                    <th id="subservicio">{{ $tarea->subservicio . '-' . $tarea->sede }}</th>
                    <th id="marca">{{ $tarea->marca }}</th>
                    <th id="envio_cliente">{{ $tarea->envio_cliente }}</th>
                    <th id="frecuencia">{{ $tarea->frecuencia }}</th>
                    <th id="dia">{{ $tarea->dia }}</th>
                    <th id="hora-limite">{{ \Carbon\Carbon::parse($tarea->hora_limite)->format('H:i') }}</th>
                    <th id="responsable">{{ $tarea->responsableId }}</th>
                    <th id="send" style="text-align:center"><button id="tr-{{ $tarea->id }}" type="button"
                            class="btn btn-dark edit" data-toggle="modal" data-target="#enviarInforme">
                            ></button></th>
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
                    <h5 class="modal-title" id="exampleModalLongTitle">Enviar informe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="rig">
                    <form method="POST" action="{{ route('users.enviar') }}" id="editForm">
                        @csrf
                        <div class="form-group row">
                            <label for="id" class="col-md-4 col-form-label text-md-right">{{ __('Id') }}</label>

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
                                class="col-md-4 col-form-label text-md-right">{{ __('Responsable Id') }}</label>

                            <div class="col-md-8">
                                <input id="responsableId" type="text"
                                    class="form-control @error('responsableId') is-invalid @enderror" name="responsableId"
                                    value="{{ Auth::user()->id }}" required autocomplete="responsableId" readonly>

                                @error('responsableId')
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
                            <label for="comentario_incidencia"
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
                            <button id="submitInforme" type="submit"
                                class="btn btn-primary mr-1">{{ __('Enviar informe') }} </button>
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
                $('#informe_id').val(data[0]);
                $('#nombre').val(data[1]);
                // $('#responsableId').val(data[8]);
            })
        })

    </script>

@endsection
