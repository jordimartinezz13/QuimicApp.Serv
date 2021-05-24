<?php

namespace App\Http\Controllers;

use App\Models\Muestra;
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
class MuestrasController extends BaseController
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
}
