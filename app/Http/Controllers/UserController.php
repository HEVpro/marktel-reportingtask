<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function perfil(){
        return view('perfil');
    }
    public function update(Request $request){
        // Conseguir usuario identificado
        $user = \Auth::user();
        $id = $user->id;

        // Validar formulario POST
        $validate = $this->validate($request, [
            'nombre' => 'required|string|max:255|unique:usuarios,nombre,'.$id,
            'usuario' => 'required|string|max:255|unique:usuarios,usuario,'.$id,
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        // Asignacion valores del formulario
        $nombre = $request->input('nombre');
        $usuario = $request->input('usuario');
        $password = Hash::make($request->input('password'));

        // Asignar nuevos valores al usuario loggeado
        $user->nombre = $nombre;
        $user->usuario = $usuario;
        $user->password = $password;

        // Ejecutar cambios en la DB
        $user->update();
        return redirect()->route('perfil')->with(['message'=>'Usuario actualizado correctamente']);

    }
    public function estado(Request $request){
        // Conseguir usuario identificado
        $user = \Auth::user();
        $id = $user->id;
        $estado = $request->input('estado');

        $user->estado = $estado;
        $user->update();
        return redirect()->route('perfil')->with(['message'=>'Estado actualizado correctamente']);
    }
}
