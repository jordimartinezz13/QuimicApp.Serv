<?php

namespace App\Http\Controllers;

use App\Models\CompuestoEnMuestra;
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
class CompuestosEnMuestraController extends BaseController
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
