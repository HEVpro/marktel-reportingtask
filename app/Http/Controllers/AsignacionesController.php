<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignaciones;
use App\Models\User;
use DB;

class AsignacionesController extends Controller
{
    public function asignaciones(){
        $user = \Auth::user();
        $id= $user->id;

        $nombres = DB::table('usuarios')
        ->select('usuario')
        ->get();

        $tareas = DB::table('tareas')
        ->leftJoin('usuarios', 'tareas.responsableId', '=', 'usuarios.id')
        ->select('tareas.id', 'tareas.nombre_informe', 'tareas.servicio', 'tareas.subservicio', 'tareas.sede', 'tareas.tipo', 'tareas.marca', 'tareas.tiempo_completar', 'tareas.envio_cliente', 'tareas.frecuencia', 'tareas.dia', 'tareas.hora_limite', 'tareas.responsableId', 'tareas.estado', 'usuarios.usuario', 'usuarios.nombre')
        ->where('usuarios.estado', '=', 'baja')
        ->orWhere('usuarios.estado', '=', 'vacaciones')
        ->get();

        return view('users.asignaciones')->with('tareas',$tareas)->with('nombres',$nombres);
    }
    public function asignar(Request $request){
        $user = \Auth::user();
        $id= $user->id;
        $asignacion = new Asignaciones;
        $inicio = $request->fecha_inicio;
        $fin = $request->fecha_fin;
        $asignado = $request->asignado;

        if(empty($inicio)){
            $inicio = Carbon::now()->startOfMonth();
        }
        if(empty($fin)){
            $fin = Carbon::now()->endOfMonth();
        }


        $username = DB::table('usuarios')
        ->where('id', '=', $id)
        ->select('usuario')
        ->get();


        $ids = DB::table('usuarios')
        ->where('usuario', '=', $asignado)
        ->select('id')
        ->get();

        $nombre_usuario = $username[0]->usuario;
        $asignacion = $ids[0]->id;

        $asignaciones = $request->tareas;
        foreach($asignaciones as $tarea){
            $report = explode('-', $tarea);
            $id_informe = $report[0];
            $nombre = $report[1];
            $responsableId = $report[2];

            Asignaciones::create([
                'id_informe' => $id_informe,
                'nombre_informe' => $nombre,
                'responsable_id' => $responsableId ,
                'backup_id' => $asignacion,
                'fecha_inicio' =>  $inicio,
                'fecha_fin' =>  $fin,
                'creador' =>  $nombre_usuario,

            ]);

        };
        return redirect()->route('asignaciones')->with(['message'=>'Tarea/s asignadas correctamente!']);

    }
}
