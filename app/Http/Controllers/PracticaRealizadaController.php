<?php

namespace App\Http\Controllers;

use App\Models\PracticaRealizada;
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
class PracticasRealizadasController extends BaseController
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
}
