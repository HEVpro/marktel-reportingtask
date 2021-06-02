<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'actividad';

    protected $fillable = ['informe', 'responsableId', 'informe_id', 'fecha', 'entregado', 'retraso', 'incidencia', 'comentario_incidencia', 'error', 'procede', 'nivel_error'];

    // Relation One to Many
     public function usuarios(){
        return $this->hasMany('App\Models\User');
    }
    // Relation Many to One
    public function user(){
        return $this->belongsTo('App\Models\User', 'responsableId');
    }
    // Relation One to Many
    public function tasks(){
        return $this->hasMany('App\Models\Task');
    }
      // Relation Many to One
      public function task(){
        return $this->belongsTo('App\Models\Task', 'informe_id');
    }


}
