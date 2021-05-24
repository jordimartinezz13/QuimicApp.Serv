<?php

namespace App\Http\Controllers;

use App\Models\Condicion;
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
class CondicionController extends BaseController
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
}
