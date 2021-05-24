<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
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
class GrupoController extends BaseController
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
        if(auth()->user()->id_profesor){
        $grupo = Grupo::find($request->id);
        $grupo->update($request->all());

        return $grupo;

    }else{
        return response()->json([
            'error' => 'No autorizado',
        ], 401);
    }
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
        if(auth()->user()->id_profesor){
        $grupo = new Grupo;
        $grupo->nombre = $request->nombre;

        $grupo->save();
        return $grupo;
    }else{
        return response()->json([
            'error' => 'No autorizado',
        ], 401);
    }
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
        if(auth()->user()->id_profesor){
        $grupo = Grupo::find($id);

        $grupo->delete();

        return $grupo;
    }else{
        return response()->json([
            'error' => 'No autorizado',
        ], 401);
    }
    }
}
