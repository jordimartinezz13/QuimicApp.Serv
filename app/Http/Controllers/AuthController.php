<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registro']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|between:2,100',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        return $this->createNewToken($token);
    }

    // /**
    //  * Register a User.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function registro(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|string|between:2,100',
    //         'password' => 'required|string|confirmed|min:6',
    //         'id_profesor' => 'integer',
    //         'id_alumno' => 'integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }

    //     $user = Usuario::create(array_merge(
    //         $validator->validated(),
    //         ['password' => bcrypt($request->password)]
    //     ));

    //     return response()->json([
    //         'message' => 'Registrado con éxito',
    //         'user' => $user
    //     ], 201);
    // }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Desconetado con éxito']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function perfilUsuario()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {

        $usuario = auth()->user();
        unset($usuario['password']);
        unset($usuario['token']);

        if (!$usuario['codigo_verificacion']) {

            if ($usuario['id_profesor']) {
                $prof = Profesor::find($usuario['id_profesor']);
                $usuario['es_admin'] = $prof['es_admin'];
            }

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                // 'expires_in' => auth()->factory()->getTTL() * 6000,
                'user' => $usuario,
            ]);
        } else {
            return response()->json([
                'error' => 'No verificado',
            ], 209);
        }
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function registro(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|between:2,100',
            'password' => 'required|string|confirmed|min:6',
            'id_profesor' => 'integer',
            'id_alumno' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = Usuario::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'Registrado con éxito',
            'user' => $user,
        ], 201);
    }

    // /**
    //  * Get a JWT via given credentials.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function register(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|string|between:2,100',
    //         'password' => 'required|string|confirmed|min:6',
    //         'id_profesor' => 'integer',
    //         'id_alumno' => 'integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }

    //     $user = Usuario::create(array_merge(
    //         $validator->validated(),
    //         ['password' => bcrypt($request->password)]
    //     ));

    //     return response()->json([
    //         'message' => 'Registrado con éxito',
    //         'user' => $user
    //     ], 201);
    // }

    // /**
    //  * Get a JWT via given credentials.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function registroAlumno(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|string|between:2,100',
    //         'password' => 'required|string|confirmed|min:6',
    //         'id_profesor' => 'integer',
    //         'id_alumno' => 'integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }

    //     $alumno = new Alumno;

    //     $alumno->id_grupo = $request->id_grupo;
    //     $alumno->nombre = $request->nombre;
    //     $alumno->apellidos = $request->apellidos;
    //     $alumno->email = $request->email;

    //     $alumno->save();

    //     $user = Usuario::create(array_merge(
    //         $validator->validated(),
    //         ['password' => bcrypt($request->password)],
    //         ['id_alumno' => $alumno->id]
    //     ));

    //     return response()->json([
    //         'message' => 'Registrado con éxito',
    //         'user' => $user
    //     ], 201);
    // }

    // /**
    //  * Get a JWT via given credentials.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function registroProfesor(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|string|between:2,100',
    //         'password' => 'required|string|confirmed|min:6',
    //         'id_profesor' => 'integer',
    //         'id_alumno' => 'integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }

    //     $profesor = new Profesor;

    //     $profesor->nombre = $request->nombre;
    //     $profesor->apellidos = $request->apellidos;
    //     $profesor->email = $request->email;
    //     $profesor->es_admin = $request->es_admin;

    //     $profesor->save();

    //     $user = Usuario::create(array_merge(
    //         $validator->validated(),
    //         ['password' => bcrypt($request->password)],
    //         ['id_profesor' => $profesor->id]
    //     ));

    //     return response()->json([
    //         'message' => 'Registrado con éxito',
    //         'user' => $user
    //     ], 201);
    // }

    // public function eliminarUsuario($id)
    // {
    //     $usuario = Usuario::find($id);

    //     if($usuario->id_profesor){
    //         $profesor = Profesor::find($usuario->id_profesor);
    //         $profesor->delete();
    //     }else{
    //         $alumno = Alumno::find($usuario->id_alumno);
    //         $alumno->delete();
    //     }
    //     $usuario->delete();

    //     return $usuario;
    // }

    // public function leerUsuario($id)
    // {
    //     $usuario = Usuario::find($id);
    //     unset($usuario['password']);
    //     unset($usuario['token']);

    //     if($usuario->id_profesor){
    //         $profesor = Profesor::find($usuario->id_profesor);
    //         unset($profesor['es_admin']);
    //         return [$usuario, $profesor];
    //     }else{
    //         $alumno = Alumno::find($usuario->id_alumno);
    //         return [$usuario, $alumno];
    //     }
    // }

    // /**
    //  * Get a JWT via given credentials.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function updateUsuario(Request $request, $id)
    // {
    //     $usuario = Usuario::find($request->id);

    //     if($request->password){
    //         //$password = bcrypt($request->password);
    //         //$usuario->update("password"->$password);
    //         $request->password = bcrypt($request->password);
    //         $usuario->update(array_merge($request->all(),
    //         ['password' => bcrypt($request->password)]));
    //     }else{
    //         $usuario->update($request->all());
    //     }

    //     unset($usuario['password']);
    //     unset($usuario['token']);

    //     if($usuario->id_alumno){
    //         $alumno = Alumno::find($usuario->id_alumno);
    //         $alumno->update($request->all());
    //         $respuesta = response()->json([
    //             'message' => 'Actualizado con éxito',
    //             'user' => [$usuario, $alumno]
    //         ], 200);
    //     }else{
    //         $profesor = Profesor::find($usuario->id_profesor);
    //         $profesor->update($request->all());
    //         unset($profesor['es_admin']);
    //         $respuesta = response()->json([
    //             'message' => 'Actualizado con éxito',
    //             'user' => [$usuario, $profesor]
    //         ], 200);
    //     }

    //     return $respuesta;
    // }
}