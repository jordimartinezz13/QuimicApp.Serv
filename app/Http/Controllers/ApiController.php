<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\CompuestoEnMuestra;
use App\Models\CompuestoQuimico;
use App\Models\Condicion;
use App\Models\Grupo;
use App\Models\Muestra;
use App\Models\Practica;
use App\Models\PracticaRealizada;
use App\Models\Profesor;
use App\Models\Usuario;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="API Química", version="1.0")
 *
 * @OA\Server(url="http://localhost/projecte_M14/quimica/apiLaravel/public")
 */
class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
    * Create a new AuthController instance.
    *
    * @return void
    */
   public function __construct()
   {
       $this->middleware('auth:api', ['except' => ['']]);
   }

    // USUARIO

    /**
     * @OA\Get(
     *   path="/api/usuarios",
     *   tags={"usuarios"},
     *   summary="Ver todos los usuarios.",
     *   @OA\Response(
     *     response=200,
     *     description="Retorna todos los usuarios.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getUsuarios()
    {
        return Usuario::all();
    }

    /**
     * @OA\Get(
     *   path="/api/usuario/{id}",
     *   tags={"usuarios"},
     *   summary="Ver un usuario concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del usuario",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna un usuario concreto.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getUsuario($id)
    {
        return Usuario::find($id);
    }

    /**
     * @OA\Put(
     *   path="/api/usuario/{id}",
     *   tags={"usuarios"},
     *   summary="Editar un usuario concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del usuario a editar",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id_profesor",
     *     description="profesor al que pertenece el usuario",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id_alumno",
     *     description="alumno al que pertenece el usuario",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="username",
     *     description="username del usuario",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="password",
     *     description="password del usuario",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="token",
     *     description="token del usuario",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna el usuario que hemos editado.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function updateUsuario(Request $request)
    {
        $usuario = Usuario::find($request->id);
        $usuario->update($request->all());

        return $usuario;
    }

    /**
     * @OA\Post(
     *   path="/api/usuario",
     *   tags={"usuarios"},
     *   summary="Insertar un nuevo usuario.",
     *   @OA\Parameter(
     *     name="username",
     *     description="username del usuario",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="password",
     *     description="password del usuario",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id_alumno",
     *     description="id del alumno asignado al usuario",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="id_profesor",
     *     description="id del profesor asignado al usuario",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="token",
     *     description="token de seguridad asignado al usuario",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna el usuario que hemos insertado.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function insertUsuario(Request $request)
    {
        $usuario = new Usuario;

        $usuario->id_profesor = $request->id_profesor;
        $usuario->id_alumno = $request->id_alumno;
        $usuario->username = $request->username;
        $usuario->password = $request->password;
        $usuario->token = $request->token;

        $usuario->save();
        return $usuario;
    }

    /**
     * @OA\Delete(
     *   path="/api/usuario/{id}",
     *   tags={"usuarios"},
     *   summary="Eliminar un usuario concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del usuario",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Se ha eliminado el usuario correctamente.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function deleteUsuario($id)
    {
        $usuario = Usuario::find($id);

        $usuario->delete();

        return $usuario;
    }

    // ALUMNO

    /**
     * @OA\Get(
     *   path="/api/alumnos",
     *   tags={"alumnos"},
     *   summary="Ver todos los alumnos.",
     *   @OA\Response(
     *     response=200,
     *     description="Retorna todos los alumnos.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getAlumnos()
    {
        return Alumno::all();
    }

    /**
     * @OA\Get(
     *   path="/api/alumno/{id}",
     *   tags={"alumnos"},
     *   summary="Ver un alumno concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del alumno",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna un alumno concreto.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getAlumno($id)
    {
        return Alumno::find($id);
    }

    /**
     * @OA\Put(
     *   path="/api/alumno/{id}",
     *   tags={"alumnos"},
     *   summary="Editar un alumno concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del alumno a editar",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id_grupo",
     *     description="grupo al que pertenece el alumno",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="nombre",
     *     description="nombre del alumno",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="apellidos",
     *     description="apellidos del alumno",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="email",
     *     description="email del alumno",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna el alumno que hemos editado.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function updateAlumno(Request $request)
    {
        $alumno = Alumno::find($request->id);
        $alumno->update($request->all());

        return $alumno;
    }

    /**
     * @OA\Post(
     *   path="/api/alumno",
     *   tags={"alumnos"},
     *   summary="Insertar un nuevo alumno.",
     *   @OA\Parameter(
     *     name="nombre",
     *     description="nombre del alumno",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="apellidos",
     *     description="apellidos del alumno",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="email",
     *     description="email del alumno",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id_grupo",
     *     description="id del grupo asignado al alumno",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *  @OA\Response(
     *     response=200,
     *     description="Retorna el alumno que hemos insertado.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function insertAlumno(Request $request)
    {
        $alumno = new Alumno;

        $alumno->id_grupo = $request->id_grupo;
        $alumno->nombre = $request->nombre;
        $alumno->apellidos = $request->apellidos;
        $alumno->email = $request->email;

        $alumno->save();
        return $alumno;
    }

    /**
     * @OA\Delete(
     *   path="/api/alumno/{id}",
     *   tags={"alumnos"},
     *   summary="Eliminar un alumno concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del alumno",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Se ha eliminado el alumno correctamente.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function deleteAlumno($id)
    {
        $alumno = Alumno::find($id);

        $alumno->delete();

        return $alumno;
    }

    // PROFESOR

    /**
     * @OA\Get(
     *   path="/api/profesores",
     *   tags={"profesores"},
     *   summary="Ver todos los profesores.",
     *   @OA\Response(
     *     response=200,
     *     description="Retorna todos los profesores.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getProfesores()
    {
        return Profesor::all();
    }

    /**
     * @OA\Get(
     *   path="/api/profesor/{id}",
     *   tags={"profesores"},
     *   summary="Ver un profesor concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del profesor",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna un profesor concreto.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getProfesor($id)
    {
        return Profesor::find($id);
    }

    /**
     * @OA\Put(
     *   path="/api/profesor/{id}",
     *   tags={"profesores"},
     *   summary="Editar un profesor concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del profesor a editar",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="es_admin",
     *     description="booleano que nos dice si el profesor es admin o no",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="nombre",
     *     description="nombre del profesor",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="apellidos",
     *     description="apellidos del profesor",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="email",
     *     description="email del profesor",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna el grupo que hemos editado.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function updateProfesor(Request $request)
    {
        $profesor = Profesor::find($request->id);
        $profesor->update($request->all());

        return $profesor;
    }

    /**
     * @OA\Post(
     *   path="/api/profesor",
     *   tags={"profesores"},
     *   summary="Insertar un nuevo profesor.",
     *   @OA\Parameter(
     *     name="nombre",
     *     description="nombre del profesor",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="apellidos",
     *     description="apellidos del profesor",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="email",
     *     description="email del profesor",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="es_admin",
     *     description="booleano que nos dice si el profesor es admin o no",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *  @OA\Response(
     *     response=200,
     *     description="Retorna el grupo que hemos insertado.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function insertProfesor(Request $request)
    {
        $profesor = new Profesor;

        $profesor->nombre = $request->nombre;
        $profesor->apellidos = $request->apellidos;
        $profesor->email = $request->email;
        $profesor->es_admin = $request->es_admin;

        $profesor->save();
        return $profesor;
    }

    /**
     * @OA\Delete(
     *   path="/api/profesor/{id}",
     *   tags={"profesores"},
     *   summary="Eliminar un profesor concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del profesor",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Se ha eliminado el profesor correctamente.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function deleteProfesor($id)
    {
        $profesor = Profesor::find($id);

        $profesor->delete();

        return $profesor;
    }

    // GRUPO

    /**
     * @OA\Get(
     *   path="/api/grupos",
     *   tags={"grupos"},
     *   summary="Ver todos los grupos.",
     *   @OA\Response(
     *     response=200,
     *     description="Retorna todos los grupos.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getGrupos()
    {
        return Grupo::all();
    }

    /**
     * @OA\Get(
     *   path="/api/grupo/{id}",
     *   tags={"grupos"},
     *   summary="Ver un grupo concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del grupo",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna un grupo concreto.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getGrupo($id)
    {
        return Grupo::find($id);
    }

    /**
     * @OA\Put(
     *   path="/api/grupo/{id}",
     *   tags={"grupos"},
     *   summary="Editar un grupo concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del grupo a editar",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="nombre",
     *     description="nombre del grupo",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Response(
     *     response=200,
     *     description="Retorna el grupo que hemos editado.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function updateGrupo(Request $request)
    {
        $grupo = Grupo::find($request->id);
        $grupo->update($request->all());

        return $grupo;
    }

    /**
     * @OA\Post(
     *   path="/api/grupo",
     *   tags={"grupoes"},
     *   summary="Insertar un nuevo grupo.",
     *   @OA\Parameter(
     *     name="nombre",
     *     description="nombre del grupo",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Response(
     *     response=200,
     *     description="Retorna el grupo que hemos insertado.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function insertGrupo(Request $request)
    {
        $grupo = new Grupo;
        $grupo->nombre = $request->nombre;

        $grupo->save();
        return $grupo;
    }

    /**
     * @OA\Delete(
     *   path="/api/grupo/{id}",
     *   tags={"grupos"},
     *   summary="Eliminar un grupo concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id del grupo",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Se ha eliminado el grupo correctamente.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function deleteGrupo($id)
    {
        $grupo = Grupo::find($id);

        $grupo->delete();

        return $grupo;
    }

    // PRACTICA

    /**
     * @OA\Get(
     *   path="/api/practicas",
     *   tags={"practicas"},
     *   summary="Ver todas las practicas.",
     *   @OA\Response(
     *     response=200,
     *     description="Retorna todas las practicas.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getPracticas()
    {
        return Practica::all();
    }

    /**
     * @OA\Get(
     *   path="/api/practica/{id}",
     *   tags={"practicas"},
     *   summary="Ver una practica concreto.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id de la practica",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna una practica concreto.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getPractica($id)
    {
        return Practica::find($id);
    }

    /**
     * @OA\Put(
     *   path="/api/practica/{id}",
     *   tags={"practicas"},
     *   summary="Editar una practica concreta.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id de la practica a editar",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id_profesor",
     *     description="profesor que ha asignado la practica",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id_compuesto_en_muestra",
     *     description="id del compuesto que pertenece a la muestra de la practica",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="fecha_inicio",
     *     description="fecha de inicio de la practica",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="fecha_fin",
     *     description="fecha de fin de la practica",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="enunciado",
     *     description="enunciado de la practica",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna la practica que hemos editado.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function updatePractica(Request $request)
    {
        $practica = Practica::find($request->id);
        $practica->update($request->all());

        return $practica;
    }

    /**
     * @OA\Post(
     *   path="/api/practica",
     *   tags={"practicas"},
     *   summary="Insertar una nueva practica.",
     *   @OA\Parameter(
     *     name="fecha_inicio",
     *     description="fecha de inicio de la practica",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="fecha_fin",
     *     description="fecha de fin de la practica",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id_compuesto_en_muestra",
     *     description="id del compuesto que pertenece a la muestra de la practica",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="id_profesor",
     *     description="id del profesor que ha asignado la practica",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *  @OA\Parameter(
     *     name="enunciado",
     *     description="enunciado de la practica",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retorna la practica que hemos insertado.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function insertPractica(Request $request)
    {
        $practica = new Practica;
        $practica->id_profesor = $request->id_profesor;
        $practica->id_compuesto_en_muestra = $request->id_compuesto_en_muestra;
        $practica->fecha_inicio = $request->fecha_inicio;
        $practica->fecha_fin = $request->fecha_fin;
        $practica->enunciado = $request->enunciado;

        $practica->save();
        return $practica;
    }

    /**
     * @OA\Delete(
     *   path="/api/practica/{id}",
     *   tags={"practicas"},
     *   summary="Eliminar una practica concreta.",
     *   @OA\Parameter(
     *     name="id",
     *     description="id de la practica",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Se ha eliminado la practica correctamente.",
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function deletePractica($id)
    {
        $practica = Practica::find($id);

        $practica->delete();

        return $practica;
    }

    // PRACTICA REALIZADA
    public function getPracticasRealizadas()
    {
        return PracticaRealizada::all();
    }

    public function getPracticaRealizada($id)
    {
        return PracticaRealizada::find($id);
    }

    public function updatePracticarealizada(Request $request)
    {
        $practicaRealizada = PracticaRealizada::find($request->id);
        $practicaRealizada->update($request->all());

        return $practicaRealizada;
    }

    public function insertPracticaRealizada(Request $request)
    {
        $practicaRealizada = new PracticaRealizada;
        $practicaRealizada->id_practica = $request->id_practica;
        $practicaRealizada->id_grupo = $request->id_grupo;
        $practicaRealizada->respuesta_alumno = $request->respuesta_alumno;
        $practicaRealizada->nota = $request->nota;
        $practicaRealizada->comentario_alumno = $request->comentario_alumno;
        $practicaRealizada->comentario_profesor = $request->comentario_profesor;
        $practicaRealizada->fichero = $request->fichero;
        $practicaRealizada->puede_proceder = $request->puede_proceder;

        $practicaRealizada->save();
        return $practicaRealizada;
    }

    public function deletePracticaRealizada($id)
    {
        $practicaRealizada = PracticaRealizada::find($id);

        $practicaRealizada->delete();

        return $practicaRealizada;
    }

    // MUESTRA
    public function getMuestras()
    {
        return Muestra::all();
    }

    public function getMuestra($id)
    {
        return Muestra::find($id);
    }

    public function updateMuestra(Request $request)
    {
        $muestra = Muestra::find($request->id);
        $muestra->update($request->all());

        return $muestra;
    }

    public function insertMuestra(Request $request)
    {
        $muestra = new Muestra;
        $muestra->nombre = $request->nombre;
        $muestra->codigo = $request->codigo;
        $muestra->comentario = $request->comentario;

        $muestra->save();

        return $muestra;
    }

    public function deleteMuestra($id)
    {
        $muestra = Muestra::find($id);

        $muestra->delete();

        return $muestra;
    }

    // CONDICIONES
    public function getCondiciones()
    {
        return Condicion::all();
    }

    public function getCondicion($id)
    {
        return Condicion::find($id);
    }

    public function updateCondicion(Request $request)
    {
        $condicion = Condicion::find($request->id);
        $condicion->update($request->all());

        return $condicion;
    }

    public function insertCondicion(Request $request)
    {
        $condicion = new Condicion;
        $condicion->longitud_columna = $request->longitud_columna;
        $condicion->diametro_interior_columna = $request->diametro_interior_columna;
        $condicion->tamano_particula = $request->tamano_particula;
        $condicion->temperatura = $request->temperatura;
        $condicion->velocidad_flujo = $request->velocidad_flujo;
        $condicion->eluyente = $request->eluyente;
        $condicion->detector_uv = $request->detector_uv;

        $condicion->save();

        return $condicion;
    }

    public function deleteCondicion($id)
    {
        $condicion = Condicion::find($id);

        $condicion->delete();

        return $condicion;
    }

    // COMPUESTO QUíMICO
    public function getCompuestosQuimicos()
    {
        return CompuestoQuimico::all();
    }

    public function getCompuestoQuimico($id)
    {
        return CompuestoQuimico::find($id);
    }

    public function updateCompuestoQuimico(Request $request)
    {
        $compuestoQuimico = CompuestoQuimico::find($request->id);
        $compuestoQuimico->update($request->all());

        return $compuestoQuimico;
    }

    public function insertCompuestoQuimico(Request $request)
    {
        $compuestoQuimico = new CompuestoQuimico;
        $compuestoQuimico->nombre = $request->nombre;
        $compuestoQuimico->formula = $request->formula;
        $compuestoQuimico->descripcion = $request->descripcion;
        $compuestoQuimico->tipo = $request->tipo;
        $compuestoQuimico->estructura = $request->estructura;

        $compuestoQuimico->save();

        return $compuestoQuimico;
    }

    public function deleteCompuestoQuimico($id)
    {
        $compuestoQuimico = CompuestoQuimico::find($id);

        $compuestoQuimico->delete();

        return $compuestoQuimico;
    }

    // COMPUESTO EN MUESTRA
    public function getCompuestosMuestras()
    {
        return CompuestoEnMuestra::all();
    }

    public function getCompuestoMuestra($id)
    {
        return CompuestoEnMuestra::find($id);
    }

    public function updateCompuestoMuestra(Request $request)
    {
        $compuestoMuestra = CompuestoEnMuestra::find($request->id);
        $compuestoMuestra->update($request->all());

        return $compuestoMuestra;
    }

    public function insertCompuestoMuestra(Request $request)
    {
        $compuestoMuestra = new CompuestoEnMuestra;
        $compuestoMuestra->nombre = $request->nombre;
        $compuestoMuestra->id_compuesto = $request->id_compuesto;
        $compuestoMuestra->id_condiciones = $request->id_condiciones;
        $compuestoMuestra->id_muestra = $request->id_muestra;
        $compuestoMuestra->cantidad = $request->cantidad;
        $compuestoMuestra->minutos = $request->minutos;
        $compuestoMuestra->altura = $request->altura;

        $compuestoMuestra->save();

        return $compuestoMuestra;
    }

    public function deleteCompuestoMuestra($id)
    {
        $compuestoMuestra = CompuestoEnMuestra::find($id);

        $compuestoMuestra->delete();

        return $compuestoMuestra;
    }
}
