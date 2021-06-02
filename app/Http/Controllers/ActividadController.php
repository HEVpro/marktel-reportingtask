<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Task;
use DB;
use Carbon\Carbon;

class ActividadController extends Controller
{
    public function actividad(Request $request){
        $user = \Auth::user();
        $id = $user->id;
        $rol = $user->rol;
        $actividades;

        $nombres = DB::table('usuarios')
        ->select('usuario')
        ->get();

        $inicio = $request->get('fecha_inicio');
        $fin = $request->get('fecha_fin');
        if(empty($inicio)){
            $inicio = Carbon::now()->startOfMonth();
        }
        if(empty($fin)){
            $fin = Carbon::now()->endOfMonth();
        }

         if($rol === 'admin'){

            if($request->get('retraso') &&  $request->get('incidencia') && $request->get('usuario') && $request->get('error')){
                $actividades =  DB::table('actividad')
                ->leftJoin('usuarios', 'actividad.responsableId', '=', 'usuarios.id')
                ->select('actividad.id','actividad.informe', 'usuarios.usuario', 'actividad.informe_id', 'actividad.fecha', 'actividad.entregado', 'actividad.retraso', 'actividad.incidencia', 'actividad.comentario_incidencia', 'actividad.error', 'actividad.procede', 'actividad.nivel_error')
                ->where('retraso', '=', $request->get('retraso'))
                ->where('procede', '=', $request->get('error'))
                ->where('incidencia', '=', $request->get('incidencia'))
                ->where('usuarios.usuario', '=', $request->get('usuario'))
                ->whereBetween('fecha', [$inicio, $fin])
                ->orderBy('fecha', 'asc')
                ->get();
            }else{
                $actividades =  DB::table('actividad')
                ->leftJoin('usuarios', 'actividad.responsableId', '=', 'usuarios.id')
                ->select('actividad.id','actividad.informe', 'usuarios.usuario', 'actividad.informe_id', 'actividad.fecha', 'actividad.entregado', 'actividad.retraso', 'actividad.incidencia', 'actividad.comentario_incidencia', 'actividad.error', 'actividad.procede', 'actividad.nivel_error')
                ->orderBy('fecha', 'asc')
                ->get();
            }


        }else{
            $actividades =  DB::table('actividad')
            ->leftJoin('usuarios', 'actividad.responsableId', '=', 'usuarios.id')
            ->select('actividad.id','actividad.informe', 'usuarios.usuario', 'actividad.informe_id', 'actividad.fecha', 'actividad.entregado', 'actividad.retraso', 'actividad.incidencia', 'actividad.comentario_incidencia', 'actividad.error', 'actividad.procede', 'actividad.nivel_error')
            ->where('actividad.responsableId', '=', $id)
            ->where('actividad.fecha', '=', Carbon::today())
            ->orderBy('fecha', 'asc')
            ->get();
        }

        return view('users.actividad')->with('actividades',$actividades)->with('nombres',$nombres)->with('inicio',$inicio)->with('fin',$fin);

    }
    public function errores(Request $request){

        $actividad = Activity::find($request->actividadId);

        $actividad->error=$request->error;
        $actividad->procede=$request->procede;
        $actividad->nivel_error=$request->nivel_error;

        $actividad->save();

        return redirect()->route('users.actividad')->with(['message'=>'Error registrado correctamente']);


    }
    public function modificar(Request $request){

        $actividad = Activity::find($request->factividadId);

        $actividad->incidencia=$request->incidencia;
        $actividad->comentario_incidencia=$request->comentario_incidencia;

        $actividad->save();

        return redirect()->route('users.actividad')->with(['message'=>'Registro modificado correctamente']);


    }

    public function enviar(Request $request){

        $Activity = new Activity;

        $envio = new \DateTime();
        $retraso;
        $tarea = Task::select('hora_limite')
                        ->where('id', '=', $request->informe_id)
                       ->get();

        $limite = new \DateTime($tarea[0]->hora_limite);
        if($limite->format('H:i') === '00:00'){
            $retraso = "NO";
        }else{
            $interval = $envio->diff($limite);
            $minutos = $interval->d * 24 * 60;
            $minutos += $interval->h * 60;
            $minutos += $interval->i;

            if($minutos > 20){
                $retraso = "SI";
            }
        }
        $enviados = count(DB::table('actividad')
        ->select('informe_id')
        ->where('informe_id', '=', $request->informe_id)
        ->where('fecha', '=', Carbon::today())
        ->get());

        if($enviados > 0){
            return redirect()->route('users.tareas')->with(['enviado'=>'Este informe ya ha sido enviado']);
        }


         // Operaciones de fecha + insertar en la tabla actividad
        Activity::create([
            'informe' => $request['nombre'],
            'responsableId' => $request['responsableId'],
            'informe_id' => $request['informe_id'],
            'fecha' => $envio,
            'entregado' => "SI",
            'retraso' => $retraso,
            'incidencia' => $request['incidencia'],
            'comentario_incidencia' => $request['comentario_incidencia'],
        ]);

        return redirect()->route('users.tareas')->with(['message'=>'Informe enviado correctamente']);
    }
}
