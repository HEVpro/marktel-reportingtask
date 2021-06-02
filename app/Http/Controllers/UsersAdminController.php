<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersAdminController extends Controller
{
    public function users(){
        $usuarios = User::all();

        return view('admin.usuarios', array('usuarios' => $usuarios));
    }
    public function editar(Request $request){
        $id = $request->input('id');
        $user = User::find($id);


        // Validar formulario POST
        $request->validate([
            'id' => 'required|unique:usuarios,id,'.$id,
            'nombre' => 'required|string|max:255|unique:usuarios,nombre,'.$id,
            'usuario' => 'required|string|max:255|unique:usuarios,usuario,'.$id,
            'rol' => 'string|max:255',
            'estado' => 'string|max:255',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $nombre = $request->input('nombre');
        $usuario = $request->input('usuario');
        $rol = $request->input('rol');
        $estado = $request->input('estado');
        $password = Hash::make($request->input('password'));

         // Asignar nuevos valores al usuario a editar
         $user->nombre = $nombre;
         $user->usuario = $usuario;
         $user->rol = $rol;
         $user->estado = $estado;
         $user->password = $password;

         $user->save();
         return redirect()->route('admin.usuarios')->with(['message'=>'Usuario modificado correctamente']);


    }
    public function crear(Request $request){

            $usuario = new User;
           // Validar formulario POST
           $validate = $this->validate($request, [
            'nombre' => 'required|string|max:255|unique:usuarios',
            'usuario' => 'required|string|max:255|unique:usuarios',
            'rol' => 'string|max:255',
            'estado' => 'string|max:255',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

            // Guardar usuario
            User::create([
                'nombre' => $request['nombre'],
                'usuario' => $request['usuario'],
                'rol' => $request['rol'],
                'estado' => $request['estado'],
                'password' => Hash::make($request['password']),
            ]);

            return redirect()->route('admin.usuarios')->with(['message'=>'Usuario creado correctamente']);
    }
    public function eliminar($id){
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admin.usuarios')->with(['message'=>'Usuario eliminado correctamente']);
    }
}
