<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Task;
use DB;
use Carbon\Carbon;

class IncentivosController extends Controller
{
    public function incentivos(Request $request){

        $usuario_id;
        $usuario;
        $diarios;
        $semanales;
        $bi_semanal;
        $mensual;
        $entregados;
        $retrasos;
        $leves;
        $medios;
        $graves;
        $habiles = $request->get('habiles');
        $semanas = $request->get('semanas');
        $user = $request->get('usuario');

        $inicio = $request->get('fecha_inicio');
        $fin = $request->get('fecha_fin');

        if(empty($inicio)){
            $inicio = Carbon::now()->startOfMonth();
        }
        if(empty($fin)){
            $fin = Carbon::now()->endOfMonth();
        }
        if(empty($user)){
            $usuario = 'GLOBAL';
            $usuario_id = 0;

        }else{
            $query = DB::table('usuarios')->select('nombre', 'id')->where('usuario', '=', $user)->get();
            $usuario_id = $query[0]->id;
            $usuario = $query[0]->nombre;
        }
        if(empty($habiles)){
            $habiles = 22;
        }
        if(empty($semanas)){
            $semanas = 4;
        }
        if(empty($usuario_id)){
            $diarios = count(DB::table('tareas')->select('id')->where('frecuencia', '=', 'DIARIO')->get())*$habiles;
            $semanales = count(DB::table('tareas')->select('id')->where('frecuencia', '=', 'SEMANAL')->get())*$semanas;
            $bi_semanal = count(DB::table('tareas')->select('id')->where('frecuencia', '=', '2X SEMANA')->get())*($semanas*2);
            $mensuales = count(DB::table('tareas')->select('id')->where('frecuencia', '=', 'MENSUAL')->get());
            $entregados = count(DB::table('actividad')->select('id') ->whereBetween('fecha', [$inicio, $fin])->get());
            $retrasos = count(DB::table('actividad')->select('id') ->whereBetween('fecha', [$inicio, $fin])->where('retraso', '=', 'SI')->get());
            $leves =count(DB::table('actividad')->select('id')->where('procede', '=', 'SI')->where('nivel_error', '=', 'LEVE')  ->whereBetween('fecha', [$inicio, $fin])->get());
            $medios =count(DB::table('actividad')->select('id')->where('procede', '=', 'SI')->where('nivel_error', '=', 'MEDIO')  ->whereBetween('fecha', [$inicio, $fin])->get());
            $graves =count(DB::table('actividad')->select('id')->where('procede', '=', 'SI')->where('nivel_error', '=', 'GRAVE')  ->whereBetween('fecha', [$inicio, $fin])->get());
        }else{
            $query = DB::table('usuarios')->select('nombre', 'id')->where('usuario', '=', $user)->get();
            $usuario = $query[0]->nombre;

            $diarios = count(DB::table('tareas')->select('id')->where('frecuencia', '=', 'DIARIO')->where('responsableId', '=', $usuario_id) ->get())*$habiles;
            $semanales = count(DB::table('tareas')->select('id')->where('frecuencia', '=', 'SEMANAL')->where('responsableId', '=', $usuario_id)->get())*$semanas;
            $bi_semanal = count(DB::table('tareas')->select('id')->where('frecuencia', '=', '2X SEMANA')->where('responsableId', '=', $usuario_id)->get())*($semanas*2);
            $mensuales = count(DB::table('tareas')->select('id')->where('frecuencia', '=', 'MENSUAL')->where('responsableId', '=',$usuario_id)->get());
            $entregados = count(DB::table('actividad')->select('id')->where('responsableId', '=', $usuario_id) ->whereBetween('fecha', [$inicio, $fin])->get());
            $retrasos = count(DB::table('actividad')->select('id')->where('retraso', '=', 'SI')->where('responsableId', '=',$usuario_id) ->whereBetween('fecha', [$inicio, $fin])->get());
            $leves =count(DB::table('actividad')->select('id')->where('procede', '=', 'SI')->where('nivel_error', '=', 'LEVE')->where('responsableId', '=',$usuario_id)  ->whereBetween('fecha', [$inicio, $fin])->get());
            $medios =count(DB::table('actividad')->select('id')->where('procede', '=', 'SI')->where('nivel_error', '=', 'MEDIO')->where('responsableId', '=',$usuario_id)  ->whereBetween('fecha', [$inicio, $fin])->get());
            $graves =count(DB::table('actividad')->select('id')->where('procede', '=', 'SI')->where('nivel_error', '=', 'GRAVE')->where('responsableId', '=',$usuario_id)  ->whereBetween('fecha', [$inicio, $fin])->get());
        }


        $nombres = DB::table('usuarios')
        ->select('usuario')
        ->get();


        $total_informes = $diarios+$semanales+$bi_semanal+$mensuales;


        return view('admin.incentivos')
                ->with('nombres', $nombres)
                ->with('usuario', $usuario)
                ->with('total_informes', $total_informes)
                ->with('entregados', $entregados)
                ->with('retrasos', $retrasos)
                ->with('habiles', $habiles)
                ->with('semanas', $semanas)
                ->with('inicio', $inicio)
                ->with('fin', $fin)
                ->with('leves', $leves)
                ->with('medios', $medios)
                ->with('graves', $graves);
    }
}
