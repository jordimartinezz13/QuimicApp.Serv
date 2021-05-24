<?php

namespace App\Http\Controllers;

use App\Models\CompuestoQuimico;
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
class CompuestoQuimicoController extends BaseController
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
        if (auth()->user()->id_profesor) {
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
    }else{

        return response()->json([
            'error' => 'No autorizado',
        ], 401);
    }
    }

    public function deleteCompuestoMuestra($id)
    {
        if (auth()->user()->id_profesor) {
        $compuestoMuestra = CompuestoEnMuestra::find($id);

        $compuestoMuestra->delete();

        return $compuestoMuestra;
    }else{

        return response()->json([
            'error' => 'No autorizado',
        ], 401);
    }
    }
}
