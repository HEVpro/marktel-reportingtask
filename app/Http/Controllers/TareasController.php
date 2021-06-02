<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Asignaciones;

class TareasController extends Controller
{
    public function tareas(Request $request){
        $user = \Auth::user();
        $id= $user->id;
        $enviados = 0;
        $tareas;

        $enviados = count(DB::table('actividad')
        ->select('informe_id')
        ->where('responsableId', '=', $id)
        ->where('fecha', '=', Carbon::today())
        ->get());

        $marcados = DB::table('actividad')
        ->select('informe_id')
        ->where('responsableId', '=', $id)
        ->where('fecha', '=', Carbon::today())
        ->get();


        if($request->get('diarios')){
            $asignaciones = DB::table('asignaciones')
            ->leftJoin('tareas', 'asignaciones.id_informe', '=', 'tareas.id')
            ->where('backup_id', '=', $id)
            ->where('fecha_fin', '>', Carbon::today())
            ->where('estado', '=', 'ACTIVO')
            ->where('frecuencia', '=', 'DIARIO')
            ->select('tareas.id', 'tareas.nombre_informe', 'servicio', 'subservicio', 'sede', 'marca', 'envio_cliente', 'frecuencia', 'dia', 'hora_limite', 'responsableId', 'estado');

            $tareas = DB::table('tareas')
                ->select('id', 'nombre_informe', 'servicio', 'subservicio', 'sede', 'marca', 'envio_cliente', 'frecuencia', 'dia', 'hora_limite', 'responsableId', 'estado')
                ->where('responsableId', '=', $id)
                ->where('estado', '=', 'ACTIVO')
                ->where('frecuencia', '=', 'DIARIO')
                ->union($asignaciones)
                ->get();

        }else{
            $asignaciones = DB::table('asignaciones')
            ->leftJoin('tareas', 'asignaciones.id_informe', '=', 'tareas.id')
            ->where('backup_id', '=', $id)
            ->where('fecha_fin', '>', Carbon::today())
            ->where('estado', '=', 'ACTIVO')
            ->select('tareas.id', 'tareas.nombre_informe', 'servicio', 'subservicio', 'sede', 'marca', 'envio_cliente', 'frecuencia', 'dia', 'hora_limite', 'responsableId', 'estado');

            $tareas = DB::table('tareas')
                ->select('id', 'nombre_informe', 'servicio', 'subservicio', 'sede', 'marca', 'envio_cliente', 'frecuencia', 'dia', 'hora_limite', 'responsableId', 'estado')
                ->where('responsableId', '=', $id)
                ->where('estado', '=', 'ACTIVO')
                ->union($asignaciones)
                ->get();
        }

        $reports = count($tareas);

        return view('users.tareas')->with('tareas',$tareas)->with('reports', $reports)->with('enviados', $enviados)->with('marcados', $marcados);

    }
    public function editar(Request $request){

        $id = $request->get('id_informe');
        $usuario = $request->get('usuario');

        $tareas;

        if($id){
            $tareas = DB::table('tareas')
            ->leftJoin('usuarios', 'tareas.responsableId', 'usuarios.id')
            ->select('tareas.id', 'tareas.nombre_informe', 'tareas.servicio', 'tareas.subservicio', 'tareas.sede', 'tareas.tipo', 'tareas.marca', 'tareas.tiempo_completar', 'tareas.envio_cliente', 'tareas.frecuencia', 'tareas.dia', 'tareas.hora_limite', 'tareas.responsableId', 'tareas.estado', 'usuarios.usuario', 'usuarios.nombre')
            ->where('tareas.id', '=', $id)
            ->orderBy('tareas.id', 'asc')
            ->get();
        }else{
            $tareas = DB::table('tareas')
            ->leftJoin('usuarios', 'tareas.responsableId', 'usuarios.id')
            ->select('tareas.id', 'tareas.nombre_informe', 'tareas.servicio', 'tareas.subservicio', 'tareas.sede', 'tareas.tipo', 'tareas.marca', 'tareas.tiempo_completar', 'tareas.envio_cliente', 'tareas.frecuencia', 'tareas.dia', 'tareas.hora_limite', 'tareas.responsableId', 'tareas.estado', 'usuarios.usuario', 'usuarios.nombre')
            ->orderBy('tareas.id', 'asc')
            ->get();
        };
        if($usuario){
            $tareas = DB::table('tareas')
            ->leftJoin('usuarios', 'tareas.responsableId', 'usuarios.id')
            ->select('tareas.id', 'tareas.nombre_informe', 'tareas.servicio', 'tareas.subservicio', 'tareas.sede', 'tareas.tipo', 'tareas.marca', 'tareas.tiempo_completar', 'tareas.envio_cliente', 'tareas.frecuencia', 'tareas.dia', 'tareas.hora_limite', 'tareas.responsableId', 'tareas.estado', 'usuarios.usuario', 'usuarios.nombre')
            ->where('usuario', '=', $usuario)
            ->orderBy('tareas.id', 'asc')
            ->get();
        }


         $nombres = DB::table('usuarios')
        ->select('usuario')
        ->get();

        return view('admin.tareas')->with('tareas', $tareas)->with('nombres', $nombres);
    }
    public function modificar(Request $request){

        $tarea = Task::find($request->informe_id);

        $nuevoResponsable = DB::table('usuarios')
        ->select('id')
        ->where('usuario', '=', $request->fresponsable)
        ->get();

        $tarea->tiempo_completar=$request->tiempoCompletar;
        $tarea->envio_cliente=$request->envio;
        $tarea->frecuencia=$request->frecuencia;
        $tarea->dia=$request->fdia;
        $tarea->hora_limite=$request->hora;
        $tarea->responsableId=$nuevoResponsable[0]->id;
        $tarea->estado=$request->estado;

        $tarea->save();

        return redirect()->route('tareas.editar')->with(['message'=>'Tarea modificada correctamente']);


    }
    public function crear(Request  $request){
        $tarea = new Task;

        $responsable = $request->fresponsable;

        $responsableId = DB::table('usuarios')->select('id')->where('usuario', '=', $responsable)->get();

        Task::create([
            'nombre_informe' => $request->c_nombre_informe,
            'servicio' => $request->c_servicio,
            'subservicio' => $request->c_subservicio,
            'sede' => $request->c_sede,
            'tipo' => $request->c_tipo,
            'marca' => $request->c_marca,
            'tiempo_completar' => $request->tiempoCompletar,
            'envio_cliente' => $request->envio,
            'frecuencia' => $request->frecuencia,
            'dia' => $request->fdia,
            'hora_limite' => $request->hora,
            'responsableId' => $responsableId[0]->id,
            'estado' => $request->estado,

        ]);

        return redirect()->route('tareas.editar')->with(['message'=>'Tarea creada correctamente']);
    }
}
