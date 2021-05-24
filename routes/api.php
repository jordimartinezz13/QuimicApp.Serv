<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompuestoQuimicoController;
use App\Http\Controllers\CompuestosEnMuestraController;
use App\Http\Controllers\CondicionController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\MuestrasController;
use App\Http\Controllers\PracticaController;
use App\Http\Controllers\PracticasRealizadasController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
//     //api/auth/registro....
// ], function ($router) {

//AUTH
// Route::post('auth/register', [AuthController::class, 'registro']); //NOO BORRAR ESTA LÍNEA
Route::post('auth/login', [AuthController::class, 'login']); //OK
Route::post('auth/logout', [AuthController::class, 'logout']); //OK
Route::post('auth/refresh', [AuthController::class, 'refresh']); //OK
Route::get('auth/perfil-usuario', [AuthController::class, 'perfilUsuario']); //OK

// });
//Route::post('auth/registroAlumno', [AuthController::class, 'registroAlumno']);

//USUARIO
//Route::post('/register', [UsuarioController::class, 'register']);//ESTA COMENTAR, CREA USUARIOS GENERALES
Route::get('/usuarios', [UsuarioController::class, 'getUsuarios']); //OK
Route::post('/register-alumno', [UsuarioController::class, 'registerAlumno']); //ESTA COMENTAR
Route::post('/register-profesor', [UsuarioController::class, 'registerProfesor']); //ESTA COMENTAR
Route::delete('/delete-usuario/{id}', [UsuarioController::class, 'deleteUsuario']); //OK
//Route::delete('/delete-usuario-al/{id}', [UsuarioController::class, 'deleteUsuarioAlum']);//ESTA COMENTAR
//Route::delete('/delete-usuario-pr/{id}', [UsuarioController::class, 'deleteUsuarioProf']);//ESTA COMENTAR
Route::get('/usuario/{id}', [UsuarioController::class, 'getUsuario']); //OK
Route::put('/update-usuario/{id}', [UsuarioController::class, 'updateUsuario']); //OK
Route::get('/usr/co_vf/{ref}', [UsuarioController::class, 'verifyUsuario']); //OK
Route::get('/group-usuario', [UsuarioController::class, 'getGrupoUsuario']); //OK
Route::get('/update-pw/{id}', [UsuarioController::class, 'petActContra']); //OK
Route::get('/mail-pw/{id}', [UsuarioController::class, 'petActContra1']); //OK
Route::put('/usr/co-pw/{id}', [UsuarioController::class, 'actCnt']); //OK

//ALUMNO

// Route::get('/alumnos', [AlumnoController::class, 'getAlumnos']);
// Route::get('/alumno/{id}', [AlumnoController::class, 'getAlumno']);
// Route::put('/alumno/{id}', [AlumnoController::class, 'updatealumno']);
// Route::post('/alumno', [AlumnoController::class, 'insertAlumno']);
// Route::delete('/alumno/{id}', [AlumnoController::class, 'deleteAlumno']);

//PROFESOR

 Route::get('/profesores', [ProfesorController::class, 'getProfesores']);//OK
// Route::get('/profesor/{id}', [ProfesorController::class, 'getProfesor']);
// Route::put('/profesor/{id}', [ProfesorController::class, 'updateProfesor']);
// Route::post('/profesor', [ProfesorController::class, 'insertProfesor']);
// Route::delete('/profesor/{id}', [ProfesorController::class, 'deleteProfesor']);

//GRUPO

Route::get('/grupos', [GrupoController::class, 'getGrupos']);
//Route::get('/grupo/{id}', [GrupoController::class, 'getGrupo']);
Route::put('/grupo/{id}', [GrupoController::class, 'updateGrupo']); //OK
Route::post('/grupo', [GrupoController::class, 'insertGrupo']); //OK
Route::delete('/grupo/{id}', [GrupoController::class, 'deleteGrupo']); //OK

//PRACTICA

Route::get('/practicas1', [PracticaController::class, 'getPracticas1']);//OK
//Route::get('/practicas', [PracticaController::class, 'getPracticas']);
Route::get('/practica/{id}', [PracticaController::class, 'getPractica']);
Route::put('/practica/{id}', [PracticaController::class, 'updatePractica']);//OK
Route::post('/practica', [PracticaController::class, 'insertPractica']);//OK
Route::delete('/practica/{id}', [PracticaController::class, 'deletePractica']);//OK

//PRACTICA REALIZADA

Route::get('/practicas-realizadas', [PracticasRealizadasController::class, 'getPracticasRealizadas']);
Route::get('/practica-realizada/{id}', [PracticasRealizadasController::class, 'getPracticaRealizada']);
Route::put('/practica-realizada/{id}', [PracticasRealizadasController::class, 'updatePracticaRealizada']);
Route::post('/practica-realizada', [PracticasRealizadasController::class, 'insertPracticaRealizada']);
Route::delete('/practica-realizada/{id}', [PracticasRealizadasController::class, 'deletePracticaRealizada']);

//MUESTRA

Route::get('/muestras', [MuestrasController::class, 'getMuestras']);
Route::get('/muestra/{id}', [MuestrasController::class, 'getMuestra']);
Route::put('/muestra/{id}', [MuestrasController::class, 'updateMuestra']);
Route::post('/muestra', [MuestrasController::class, 'insertMuestra']);
Route::delete('/muestra/{id}', [MuestrasController::class, 'deleteMuestra']);

//CONDICION

Route::get('/condiciones', [CondicionController::class, 'getCondiciones']);
Route::get('/condicion/{id}', [CondicionController::class, 'getCondicion']);
Route::put('/condicion/{id}', [CondicionController::class, 'updateCondicion']);
Route::post('/condicion', [CondicionController::class, 'insertCondicion']);
Route::delete('/condicion/{id}', [CondicionController::class, 'deleteCondicion']);

//COMPUESTO QUÍMICO

Route::get('/compuestos-quimicos', [CompuestoQuimicoController::class, 'getCompuestosQuimicos']);
Route::get('/compuesto-quimico/{id}', [CompuestoQuimicoController::class, 'getCompuestoQuimico']);
Route::put('/compuesto-quimico/{id}', [CompuestoQuimicoController::class, 'updateCompuestoQuimico']);
Route::post('/compuesto-quimico', [CompuestoQuimicoController::class, 'insertCompuestoQuimico']);//OK
Route::delete('/compuesto-quimico/{id}', [CompuestoQuimicoController::class, 'deleteCompuestoQuimico']);//OK

//COMPUESTO EN MUESTRA

Route::get('/compuestos-muestras', [CompuestosEnMuestraController::class, 'getCompuestosMuestras']);
Route::get('/compuesto-muestra/{id}', [CompuestosEnMuestraController::class, 'getCompuestoMuestra']);
Route::put('/compuesto-muestra/{id}', [CompuestosEnMuestraController::class, 'updateCompuestoMuestra']);
Route::post('/compuesto-muestra', [CompuestosEnMuestraController::class, 'insertCompuestoMuestra']);
Route::delete('/compuesto-muestra/{id}', [CompuestosEnMuestraController::class, 'deleteCompuestoMuestra']);
