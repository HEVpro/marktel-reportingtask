<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignaciones extends Model
{
    use HasFactory;

    protected $table = 'asignaciones';

    protected $fillable = ['id_informe', 'nombre_informe', 'responsable_id', 'backup_id', 'fecha_inicio', 'fecha_fin', 'motivo', 'creador'];

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
