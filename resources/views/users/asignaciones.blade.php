@extends('layouts.master')
@push('head')
    <!-- Styles -->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">


@endpush

@section('actions')
    <h2>Asignaciones</h2>
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
    <div class="container">
        <form method="POST" action="{{ route('asignar.tareas') }}">
            @csrf
            <div class="col-12 alert alert-secondary ">
                <div class="form-check form-check-inline">

                    <div class="form-group col-md-4">
                        <label class="form-check-label" for="fecha_inicio">Fecha inicio</label>
                        <input class="form-control text-center" type="date" id="finicio" name="fecha_inicio" required
                            value={{ \Carbon\Carbon::now() }}>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-check-label" for="fecha_fin">Fecha fin</label>
                        <input class="form-control text-center" type="date" id="ffin" name="fecha_fin" required>

                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-check-label" for="asignado">Usuario </label>
                        <select class="form-control text-center" id="asignado" name="asignado" required>
                            <option value="" selected="selected"></option>
                            @foreach ($nombres as $nombre){
                                <option value="{{ $nombre->usuario }}">{{ $nombre->usuario }}</option>
                                }
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="submit" class="btn btn-primary mt-4">{{ __('Asignar') }}</button>
                    </div>
                </div>

            </div>

            <table id="datatable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Id Informe</th>
                        <th scope="col">Nombre Informe</th>
                        <th class="text-center" scope="col">Resp. Id</th>
                        <th class="text-center" scope="col">Nombre</th>
                        <th class="text-center" scope="col">Asignar</th>

                    </tr>

                </thead>
                <tbody>

                    @foreach ($tareas as $tarea)
                        <tr>
                            <th id="id"><label class="form-check-label" for="tarea">{{ $tarea->id }}</label></th>
                            <th id="nombre_Informe">
                                {{ $tarea->nombre_informe . '-' . $tarea->servicio . '-' . $tarea->marca }}

                            </th>
                            <th class="text-center" id="responsableId">{{ $tarea->responsableId }}</th>

                            <th class="text-center" id="responsableNombre"> <?php $str = explode(' ',
                                $tarea->nombre); ?>{{ $str[0] }}</th>
                            <th id="check" class="text-center"> <input class="form-check-input" type="checkbox" id="tarea"
                                    name="tareas[]"
                                    value="{{ $tarea->id . '-' . $tarea->nombre_informe . '-' . $tarea->responsableId }}">
                            </th>
                        </tr>
                    @endforeach
                </tbody>

            </table>



        </form>


    </div>
@endsection
