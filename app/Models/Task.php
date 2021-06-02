<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tareas';
    protected $dates = ['hora_limite'];

    protected $fillable = [
        'nombre_informe',
        'servicio',
        'subservicio',
        'sede',
        'tipo',
        'marca',
        'tiempo_completar',
        'envio_cliente',
        'frecuencia',
        'dia',
        'hora_limite',
        'responsableId',
        'estado',
    ];


      // Relation Many to One
      public function usuario(){
        return $this->belongsTo('App\Models\User', 'responsableId');
    }

    // Relation One to Many
    public function activities(){
        return $this->hasMany('App\Models\Activity');
    }
    // Relation Many to One
    public function activity(){
        return $this->belongsTo('App\Models\Activity', 'id');
    }


}
