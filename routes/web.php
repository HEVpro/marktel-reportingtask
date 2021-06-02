<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Import models
use App\Models\Task;
use App\Models\User;
use App\Models\Activity;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Perfil
Route::get('/perfil', [App\Http\Controllers\UserController::class, 'perfil'])->name('perfil');
Route::post('/perfil/editar', [App\Http\Controllers\UserController::class, 'update'])->name('perfil.editar');
Route::post('/perfil/estado', [App\Http\Controllers\UserController::class, 'estado'])->name('perfil.estado');

// Tareas
Route::get('/tareas', [App\Http\Controllers\TareasController::class, 'tareas'])->name('users.tareas');
Route::get('/tareas/editar', [App\Http\Controllers\TareasController::class, 'editar'])->name('tareas.editar');
Route::post('/tareas/modificar', [App\Http\Controllers\TareasController::class, 'modificar'])->name('tareas.modificar');
Route::post('/tareas/crear', [App\Http\Controllers\TareasController::class, 'crear'])->name('tareas.crear');


// Actividad
Route::get('/actividad', [App\Http\Controllers\ActividadController::class, 'actividad'])->name('users.actividad');
Route::post('/actividad/editar', [App\Http\Controllers\ActividadController::class, 'errores'])->name('users.errores');
Route::post('/actividad/enviar', [App\Http\Controllers\ActividadController::class, 'enviar'])->name('users.enviar');
Route::post('/actividad/modificar', [App\Http\Controllers\ActividadController::class, 'modificar'])->name('users.modificar');


// Asignaciones
Route::get('/asignaciones', [App\Http\Controllers\AsignacionesController::class, 'asignaciones'])->name('asignaciones');
Route::post('/asignaciones/editar', [App\Http\Controllers\AsignacionesController::class, 'asignar'])->name('asignar.tareas');

// Incentivos
Route::get('/incentivos', [App\Http\Controllers\IncentivosController::class, 'incentivos'])->name('incentivos');


// Gestión de usuarios
Route::get('/usuarios', [App\Http\Controllers\UsersAdminController::class, 'users'])->name('admin.usuarios');
Route::post('/usuarios/crear', [App\Http\Controllers\UsersAdminController::class, 'crear'])->name('admin.crear.usuarios');
Route::post('/usuarios/editar', [App\Http\Controllers\UsersAdminController::class, 'editar'])->name('admin.editar.usuarios');
Route::get('/usuarios/eliminar/{id}', [App\Http\Controllers\UsersAdminController::class, 'eliminar'])->name('admin.eliminar.usuarios');
