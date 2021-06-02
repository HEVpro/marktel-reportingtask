@extends('layouts.master')
@push('head')
    <!-- Styles -->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">


@endpush

@section('actions')
    <h2>Usuarios</h2>
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

    <table id="datatable" class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Usuario</th>
                <th scope="col">Rol</th>
                <th scope="col">Estado</th>
                <th scope="col">Modificar</th>
                <th scope="col" style="text-align:center">Eliminar</th>
                <th scope="col" style="display:none">password</th>
            </tr>

        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <th id="id">{{ $usuario->id }}</th>
                    <th id="nombre">{{ $usuario->nombre }}</th>
                    <th id="usuario">{{ $usuario->usuario }}</th>
                    <th id="rol">{{ $usuario->rol }}</th>
                    <th id="estado">{{ $usuario->estado }}</th>
                    <th id="password" style="display:none">{{ $usuario->password }}</th>
                    <th><button type="button" class="btn btn-secondary edit" data-toggle="modal"
                            data-target="#editarUsuario">
                            Editar</button></th>
                    <th style="text-align:center"><button type="button" class="btn btn-dark ">
                            <a href="{{ url('/usuarios/eliminar/' . $usuario->id) }}">X</a></button></th>
                </tr>
            @endforeach
        </tbody>

    </table>
    <!-- Button crear usuario modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUser">
        Crear nuevo usuario
    </button>
    <!-- Modal Editar usuario -->
    <div class="modal fade" id="editarUsuario" tabindex="-1" role="dialog" aria-labelledby="createUserCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="rig">
                    <form method="POST" action="{{ route('admin.editar.usuarios') }}" id="editForm">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <input id="fid" type="text"
                                    class="form-control @error('fid') is-invalid @enderror invisible" name="id"
                                    value="{{ old('Id') }}" required autocomplete="id" autofocus style="display:none">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fnombre"
                                class="col-md-5 col-form-label text-md-right">{{ __('Nombre completo') }}</label>

                            <div class="col-md-6">
                                <input id="fnombre" type="text" class="form-control @error('fnombre') is-invalid @enderror"
                                    name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>

                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fusuario"
                                class="col-md-5 col-form-label text-md-right">{{ __('Usuario') }}</label>

                            <div class="col-md-6">
                                <input id="fusuario" type="text"
                                    class="form-control @error('fusuario') is-invalid @enderror" name="usuario"
                                    value="{{ old('usuario') }}" required autocomplete="usuario" autofocus>

                                @error('fusuario')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="festado" class="col-md-5 col-form-label text-md-right">{{ __('Estado') }}</label>

                            <div class="col-md-6">
                                <select name="estado" class="custom-select" id="estado">
                                    <option selected>Escoger...</option>
                                    <option value="activo" selected="selected">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                    <option value="vacaciones">Vacaciones</option>
                                    <option value="baja">Baja</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="rol" class="col-md-5 col-form-label text-md-right">{{ __('Rol') }}</label>

                            <div class="col-md-6">
                                <select name="rol" class="custom-select" id="rol">
                                    <option selected>Escoger...</option>
                                    <option value="admin">Administrador</option>
                                    <option value="usuario" selected="selected">Usuario</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fpassword"
                                class="col-md-5 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="fpassword" type="password"
                                    class="form-control @error('fpassword') is-invalid @enderror" name="password" required
                                    autocomplete="current-password">

                                @error('fpassword')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm"
                                class="col-md-5 col-form-label text-md-right">{{ __('Confirmar contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">

                            <div class="col-md-6">
                                <input type="checkbox" id="checkPass" aria-label="Checkbox for following text input"
                                    onclick="mostrarPass()">
                                Mostrar contraseña
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary mr-1">{{ __('Guardar cambios') }}</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Create User -->
    <div class="modal fade" id="createUser" tabindex="-1" role="dialog" aria-labelledby="createUserCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Crear Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="rig">
                    <form method="POST" action="{{ route('admin.crear.usuarios') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="nombre"
                                class="col-md-5 col-form-label text-md-right">{{ __('Nombre completo') }}</label>

                            <div class="col-md-6">
                                <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>

                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="usuario"
                                class="col-md-5 col-form-label text-md-right">{{ __('Usuario') }}</label>

                            <div class="col-md-6">
                                <input id="usuario" type="text" class="form-control @error('usuario') is-invalid @enderror"
                                    name="usuario" value="{{ old('usuario') }}" required autocomplete="usuario"
                                    autofocus>

                                @error('usuario')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="estado" class="col-md-5 col-form-label text-md-right">{{ __('Estado') }}</label>

                            <div class="col-md-6">
                                <select name="estado" class="custom-select" id="estado">
                                    <option selected>Escoger...</option>
                                    <option value="activo" selected="selected">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                    <option value="vacaciones">Vacaciones</option>
                                    <option value="baja">Baja</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="rol" class="col-md-5 col-form-label text-md-right">{{ __('Rol') }}</label>

                            <div class="col-md-6">
                                <select name="rol" class="custom-select" id="rol">
                                    <option selected>Escoger...</option>
                                    <option value="admin">Administrador</option>
                                    <option value="usuario">Usuario</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password"
                                class="col-md-5 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="cpassword" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm"
                                class="col-md-5 col-form-label text-md-right">{{ __('Confirmar contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm2" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="col-md-6">
                                <input type="checkbox" aria-label="Checkbox for following text input"
                                    onclick="myFunction()">
                                Mostrar contraseña
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary mr-1">{{ __('Crear usuario') }}</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>

                    </form>
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

                    $('#fid').val(data[0]);
                    $('#fnombre').val(data[1]);
                    $('#fusuario').val(data[2]);
                    $('#fpassword').val(data[5]);
                    $('#password-confirm').val(data[5]);

                })
            })

        </script>
        <script>
            function mostrarPass() {
                var x = document.getElementById("fpassword");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
                var x = document.getElementById("password-confirm");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }

            function myFunction() {
                var x = document.getElementById("cpassword");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
                var x = document.getElementById("password-confirm2");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }

        </script>
    @stop
