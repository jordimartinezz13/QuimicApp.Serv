<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Usuario;

/**
 * @OA\Info(title="API Química", version="1.0")
 *
 * @OA\Server(url="http://localhost/projecte_M14/quimica/apiLaravel/public")
 */
class AlumnoController extends BaseController
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
        $alumnos = Alumno::all();

        foreach ($alumnos as &$valor) {
            $usuario = Usuario::where('id_alumno', $valor->id)->first();
            //array_push($valor, "nombreUsuario"=>$usuario->username);
            $valor["nombreUsuario"] = $usuario->username;
            $valor["idUsuario"] = $usuario->id;
            
        }

        return $alumnos;
        //return Alumno::all();
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
}
