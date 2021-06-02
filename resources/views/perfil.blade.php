@extends('layouts.master')

@section('actions')

    <div class="d-flex w-100" align="left">

        <div class="card-body">

            <h2 class="card-title">Perfil</h2>
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <h3 class="card-title">Estado: {{ strtoupper(Auth::user()->estado) }}</h3>
            <form method="POST" action="{{ route('perfil.estado') }}">
                @csrf

                <div class="form-group row">
                    <label for="estado" class="col-md-4 col-form-label text-md-right">{{ __('Cambiar estado') }}</label>
                    <div class="col-md-6">
                        <select id="estado" class="form-control @error('estado') is-invalid @enderror" name="estado"
                            required autocomplete="estado" autofocus>
                            <option selected>Selecciona una opción...</option>
                            <option value="vacaciones">Vacaciones</option>
                            <option value="activo">Activo</option>
                        </select>
                    </div>

                    @error('estado')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Cambiar estado') }}
                        </button>
                    </div>
                </div>
            </form>
            <h3 class="card-title">Actualización del perfil</h3>
            <form method="POST" action="{{ route('perfil.editar') }}">
                @csrf
                <div class="form-group row">
                    <label for="usuario_id" class="col-md-4 col-form-label text-md-right">{{ __('Usuario Id') }}</label>

                    <div class="col-md-6">
                        <input id="usuario_id" type="text" class="form-control @error('usuario_id') is-invalid @enderror"
                            name="usuario_id" value="{{ Auth::user()->id }}" required autocomplete="usuario_id" autofocus
                            disabled=true>

                        @error('usuario_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nombre" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

                    <div class="col-md-6">
                        <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror"
                            name="nombre" value="{{ Auth::user()->nombre }}" required autocomplete="nombre" autofocus>

                        @error('nombre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="usuario" class="col-md-4 col-form-label text-md-right">{{ __('Usuario') }}</label>

                    <div class="col-md-6">
                        <input id="usuario" type="text" class="form-control @error('usuario') is-invalid @enderror"
                            name="usuario" value="{{ Auth::user()->usuario }}" required autocomplete="usuario" autofocus>

                        @error('usuario')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password"
                        class="col-md-4 col-form-label text-md-right">{{ __('Nueva contraseña') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password-confirm"
                        class="col-md-4 col-form-label text-md-right">{{ __('Confirmar contraseña') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Guardar perfil') }}
                        </button>

                    </div>
                </div>
            </form>
        </div>
    @endsection
