<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
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
class ProfesorController extends BaseController
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

    // PROFESOR

    /**
     * @OA\Get(
     *   path="/api/profesores",
     *   tags={"profesores"},
     *   summary="Ver todos los profuse App\Models\Usuario;
     *     description="Se ha producido un error.",
     *   )
     * )
     */
    public function getProfesores()
    {
        if (auth()->user()->id_profesor) {
        $profesor = Profesor::all();

        foreach ($profesor as &$valor) {
            $usuario = Usuario::where('id_profesor', $valor->id)->first();
            //array_push($valor, "nombreUsuario"=>$usuario->username);
            $valor["nombreUsuario"] = $usuario->username;
            $valor["idUsuario"] = $usuario->id;
            
        }

        return $profesor;
        //return Profesor::all();
    }else{

    return response()->json([
        'error' => 'No autorizado',
    ], 401);
}
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
}