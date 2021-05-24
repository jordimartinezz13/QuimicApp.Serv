<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Grupo;

use App\Mail\CorreosMailable;
use App\Mail\CorreosMailable1;
use Illuminate\Support\Facades\Mail;

use Exception;

class UsuarioController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'verifyUsuario', 'petActContra1', 'actCnt']]);
    }

    public function getUsuarios()
    {

        try {

            // echo auth()->user();
            // exit;

            if (auth()->user()->id_profesor) {

                $usuarios = [];
                $alumnos = Alumno::all();

                foreach ($alumnos as &$valor) {
                    $usuario = Usuario::where('id_alumno', $valor->id)->first();
                    //array_push($valor, "nombreUsuario"=>$usuario->username);
                    if ($valor->id_grupo) $valor["nombre_grupo"] = Grupo::find($valor->id_grupo)->nombre;
                    $valor["nombreUsuario"] = $usuario->username;
                    $valor["idUsuario"] = $usuario->id;
                    $valor["tipo"] = "Alumno";
                    array_push($usuarios, $valor);
                }
                if (Profesor::find(auth()->user()->id_profesor)->es_admin) {
                    $profesor = Profesor::all();

                    foreach ($profesor as &$valor) {
                        $usuario = Usuario::where('id_profesor', $valor->id)->first();
                        //array_push($valor, "nombreUsuario"=>$usuario->username);
                        $valor["nombreUsuario"] = $usuario->username;
                        $valor["idUsuario"] = $usuario->id;
                        $valor["tipo"] = "Profesor";
                        array_push($usuarios, $valor);
                    }

                    //$usuarios = array_merge($alumnos, $profesor);

                }
                return $usuarios;
            } else if (auth()->user()->id_alumno) {
                $usuarios = [];
                $alumno1 = Alumno::find(auth()->user()->id_alumno);

                if ($alumno1->id_grupo) {
                    $nombreGrupo = Grupo::find($alumno1->id_grupo)->nombre;
                } else {
                    $nombreGrupo = "Sin grupo asignado";
                }

                $alumno1["nombreUsuario"] = auth()->user()->username;
                $alumno1["nombre_grupo"] = $nombreGrupo;
                $alumno1["tipo"] = "Alumno";
                $alumno1["idUsuario"] = auth()->user()->id;
                array_push($usuarios, $alumno1);

                // if($alumno1->id_grupo){
                //     $alumnos = Alumno::where('id_grupo', $alumno1->id_grupo)->get();
                //     $nombreGrupo = Grupo::find($alumno1->id_grupo)->nombre;
                //     foreach ($alumnos as &$valor) {

                //         if($valor->id == auth()->user()->id){
                //             $usuario = Usuario::where('id_alumno', auth()->user()->id_alumno)->first();
                //             $valor["nombreUsuario"] = $usuario->username;
                //         }
                //         $valor["nombre_grupo"] = $nombreGrupo;
                //         $valor["tipo"] = "Alumno";

                //         array_push($usuarios, $valor);

                //     }
                // }else{
                //     $alumno = Alumno::find(auth()->user()->id_alumno);
                //     $usuario = Usuario::where('id_alumno', auth()->user()->id_alumno)->first();
                //     $alumno["nombreUsuario"] = $usuario->username;
                //     $alumno["nombre_grupo"] = "Sin grupo asignado";
                //     $alumno["tipo"] = "Alumno";
                //     array_push($usuarios, $alumno);
                // }
                return $usuarios;
            }

            return response()->json([
                'error' => 'No autorizado',
            ], 401);
        } catch (Exception $e) {

            return response()->json([
                'status' => 500,
                'message' => "Error",
                'data' => [
                    'error' => $e->getMessage(),
                ]
            ], 500);
        }
    }

    public function getUsuarios1()
    {
        $usuarios = [];
        $alumnos = Alumno::all();

        foreach ($alumnos as &$valor) {
            $usuario = Usuario::where('id_alumno', $valor->id)->first();
            //array_push($valor, "nombreUsuario"=>$usuario->username);
            $valor["nombreUsuario"] = $usuario->username;
            $valor["idUsuario"] = $usuario->id;
            array_push($usuarios, $valor);
        }

        $profesor = Profesor::all();

        foreach ($profesor as &$valor) {
            $usuario = Usuario::where('id_profesor', $valor->id)->first();
            //array_push($valor, "nombreUsuario"=>$usuario->username);
            $valor["nombreUsuario"] = $usuario->username;
            $valor["idUsuario"] = $usuario->id;
            array_push($usuarios, $valor);
        }

        //$usuarios = array_merge($alumnos, $profesor);
        return $usuarios;
    }


    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
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
            'user' => $user
        ], 201);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function petActContra($id)
    {
        $usuario = Usuario::find($id);


        $ok = false;

        if (auth()->user()->id == $usuario->id) {
            $ok = true;
        } else if (auth()->user()->id_profesor && $usuario->id_alumno) {
            $ok = true;
        } else if (auth()->user()->id_profesor && Profesor::find(auth()->user()->id_profesor)->es_admin) {
            $ok = true;
        }
        //return [ auth()->user()->id_profesor , Profesor::find(auth()->user()->id_profesor)->es_admin];

        if ($ok) {
            //'password' => bcrypt($request->password),
            //'codigo_verificacion' => bin2hex(random_bytes(64))
            $usuario->update(['codigo_verificacion' => bin2hex(random_bytes(64))]);

            if ($usuario->id_profesor) {
                $usuarioAP = Profesor::find($usuario->id_profesor);
            } else {
                $usuarioAP = Alumno::find($usuario->id_alumno);
            }
            $correo = new CorreosMailable1($usuario, true);

            Mail::to($usuarioAP['email'])->send($correo);

            return response()->json([
                'message' => 'Password solicitada'
            ], 201);
        } else {
            return response()->json([
                'error' => 'No autorizado',
            ], 401);
        }
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function petActContra1($email)
    {
        $usuario = null;
        $usuario1 = Profesor::where('email', $email)->first();
        if (!$usuario1) {
            $usuario1 = Alumno::where('email', $email)->first();
            if ($usuario1) {
                $usuario = Usuario::where('id_alumno', $usuario1->id)->first();
            }
        } else {
            if ($usuario1) {
                $usuario = Usuario::where('id_profesor', $usuario1->id)->first();
            }
        }

        if ($usuario) {
            //'password' => bcrypt($request->password),
            //'codigo_verificacion' => bin2hex(random_bytes(64))
            $usuario->update(['codigo_verificacion' => bin2hex(random_bytes(64))]);

            if ($usuario->id_profesor) {
                $usuarioAP = Profesor::find($usuario->id_profesor);
            } else {
                $usuarioAP = Alumno::find($usuario->id_alumno);
            }
            $correo = new CorreosMailable1($usuario, true);

            Mail::to($usuarioAP['email'])->send($correo);

            return response()->json([
                'message' => 'Password solicitada'
            ], 201);
        } else {
            return response()->json([
                'error' => 'No encontrado',
            ], 404);
        }
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actCnt(Request $request, $ref)
    {
        $usuario = Usuario::where('codigo_verificacion', $ref)->first();

        if ($usuario) {
            //'password' => bcrypt($request->password),
            //'codigo_verificacion' => bin2hex(random_bytes(64))
            $usuario->update(['password' => bcrypt($request->password)]);
            $usuario->update(['codigo_verificacion' => null]);

            return response()->json([
                'message' => 'Password cambiada'
            ], 201);
        } else {
            return response()->json([
                'error' => 'No autorizado',
            ], 401);
        }
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actCnt1(Request $request)
    {
        $usuario = Usuario::where('codigo_verificacion', $request->codigo_verificacion)->first();

        if ($usuario) {
            //'password' => bcrypt($request->password),
            //'codigo_verificacion' => bin2hex(random_bytes(64))
            $usuario->update(['password' => bcrypt($request->password)]);
            $usuario->update(['codigo_verificacion' => null]);

            return response()->json([
                'message' => 'Password cambiada'
            ], 201);
        } else {
            return response()->json([
                'error' => 'No autorizado',
            ], 401);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerAlumno(Request $request)
    {
        if (auth()->user()->id_profesor) {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|between:2,100',
                'password' => 'required|string|confirmed|min:6',
                'id_profesor' => 'integer',
                'id_alumno' => 'integer',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            // $alumno = new Alumno;

            // $alumno->id_grupo = $request->id_grupo;
            // $alumno->nombre = $request->nombre;
            // $alumno->apellidos = $request->apellidos;
            // $alumno->email = $request->email;

            // $alumno->save();

            $alumno = Alumno::create(
                [
                    'id_grupo' => $request->id_grupo,
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'email' => $request->email
                ]
            );

            $user = Usuario::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)],
                ['id_alumno' => $alumno->id],
                ['codigo_verificacion' => bin2hex(random_bytes(64))]
            ));


            $usuario = [];
            array_push($usuario, [
                'username' => $user['username'], 'nombre' => $alumno['nombre'],
                'apellidos' => $alumno['apellidos'], 'codigo_verificacion' => $user['codigo_verificacion']
            ]);

            $correo = new CorreosMailable($usuario, false);

            Mail::to($alumno['email'])->send($correo);


            return response()->json([
                'message' => 'Registrado con éxito',
                'user' => $user
            ], 201);
        }
        return response()->json([
            'error' => 'No autorizado',
        ], 401);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUsuario($ref)
    {

        $usuario = Usuario::where('codigo_verificacion', $ref)->first();
        if ($usuario) {
            $usuario->update(['codigo_verificacion' => null]);
            
            return redirect()->away('https://quimicapp.herokuapp.com/auth');
        } else {
            return redirect()->away('https://quimicapp.herokuapp.com/noauth');
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerProfesor(Request $request)
    {
        if (auth()->user()->id_profesor) {
            $prof = Profesor::where('id', auth()->user()->id_profesor)->first();
            if ($prof->es_admin) {
                $validator = Validator::make($request->all(), [
                    'username' => 'required|string|between:2,100',
                    'password' => 'required|string|confirmed|min:6',
                    'id_profesor' => 'integer',
                    'id_alumno' => 'integer',
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors()->toJson(), 400);
                }

                // $profesor = new Profesor;

                // $profesor->nombre = $request->nombre;
                // $profesor->apellidos = $request->apellidos;
                // $profesor->email = $request->email;
                // $profesor->es_admin = $request->es_admin;

                // $profesor->save();

                if ($request->es_admin != null) {
                    $profesor = Profesor::create(
                        [
                            'nombre' => $request->nombre,
                            'apellidos' => $request->apellidos,
                            'email' => $request->email,
                            'es_admin' => $request->es_admin
                        ]
                    );
                } else {
                    $profesor = Profesor::create(
                        [
                            'nombre' => $request->nombre,
                            'apellidos' => $request->apellidos,
                            'email' => $request->email,
                            'es_admin' => 0
                        ]
                    );
                }

                $user = Usuario::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)],
                    ['id_profesor' => $profesor->id],
                    ['codigo_verificacion' => bin2hex(random_bytes(64))]
                ));

                $usuario = [];
                array_push($usuario, [
                    'username' => $user['username'], 'nombre' => $profesor['nombre'],
                    'apellidos' => $profesor['apellidos'], 'codigo_verificacion' => $user['codigo_verificacion']
                ]);

                $correo = new CorreosMailable($usuario, false);

                Mail::to($profesor['email'])->send($correo);

                return response()->json([
                    'message' => 'Registrado con éxito',
                    'user' => $user
                ], 201);
            }
        }
        return response()->json([
            'error' => 'No autorizado',
        ], 401);
    }

    public function deleteUsuarioAlum($id)
    {
        $usuario = Usuario::where('id_alumno', $id)->first();
        //return $usuario;
        $alumno = Alumno::find($usuario->id_alumno);
        $alumno->delete();

        $usuario->delete();

        return $usuario;
    }

    public function deleteUsuarioProf($id)
    {
        $usuario = Usuario::find($id);
        //return $usuario;

        $profesor = Profesor::find($usuario->id_profesor);
        if ($profesor['es_admin']) {
            return response()->json([
                'Error' => 'No es posible borrar usuarios administradores'
            ], 401);
        }
        $profesor->delete();

        $usuario->delete();

        return $usuario;
    }

    public function deleteUsuario($id)
    {
        if (auth()->user()->id_profesor) {
            $usuario = Usuario::find($id);

            if ($usuario->id_profesor) {
                $profesor = Profesor::find($usuario->id_profesor);
                if ($profesor['es_admin']) {
                    return response()->json([
                        'Error' => 'No es posible borrar usuarios administradores'
                    ], 403);
                }
                $profesor->delete();
            } else {
                $alumno = Alumno::find($usuario->id_alumno);
                $alumno->delete();
            }
            $usuario->delete();

            return $usuario;
        } else if (auth()->user()->id == $id) {
            $usuario = Usuario::find(auth()->user()->id);

            if ($usuario->id_profesor) {
                $profesor = Profesor::find($usuario->id_profesor);
                if ($profesor['es_admin']) {
                    return response()->json([
                        'Error' => 'No es posible borrar usuarios administradores'
                    ], 401);
                }
                $profesor->delete();
            } else {
                $alumno = Alumno::find($usuario->id_alumno);
                $alumno->delete();
            }
            $usuario->delete();

            return $usuario;
        } else {
            return response()->json([
                'error' => 'No autorizado',
            ], 401);
        }
    }

    public function getUsuario($id)
    {
        if (auth()->user()->id_profesor) {
            $usuario = Usuario::find($id);
            unset($usuario['password']);
            unset($usuario['codigo_verificacion	']);

            if ($usuario->id_profesor) {
                $profesor = Profesor::find($usuario->id_profesor);
                unset($profesor['es_admin']);
                return [$usuario, $profesor];
            } else {
                $alumno = Alumno::find($usuario->id_alumno);
                return [$usuario, $alumno];
            }
        } else {
            return response()->json([
                'error' => 'No autorizado',
            ], 401);
        }
    }

    public function getGrupoUsuario()
    {
        if (auth()->user()->id_alumno) {
            $usuarios = [];
            $usuario = Usuario::find(auth()->user()->id);
            $alumno1 = Alumno::find($usuario->id_alumno);

            if ($alumno1->id_grupo) {
                $alumnos = Alumno::where('id_grupo', $alumno1->id_grupo)->get();
                $nombreGrupo = Grupo::find($alumno1->id_grupo)->nombre;
                foreach ($alumnos as &$valor) {
                    if ($valor->id == auth()->user()->id) {
                        $usuario = Usuario::where('id_alumno', auth()->user()->id_alumno)->first();
                        $valor["nombreUsuario"] = $usuario->username;
                    }
                    $valor["nombre_grupo"] = $nombreGrupo;
                    $valor["tipo"] = "Alumno";

                    array_push($usuarios, $valor);
                }
            } else {
                $alumno = Alumno::find(auth()->user()->id_alumno);
                $usuario = Usuario::where('id_alumno', auth()->user()->id_alumno)->first();
                $alumno["nombreUsuario"] = $usuario->username;
                $alumno["nombre_grupo"] = "Sin grupo asignado";
                $alumno["tipo"] = "Alumno";
                array_push($usuarios, $alumno);
            }
            return $usuarios;
        } else if (auth()->user()->id_profesor) {
            return Grupo::all();
        } else {
            return response()->json([
                'error' => 'No autorizado',
            ], 401);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUsuario(Request $request, $id)
    {
        if (auth()->user()->id_profesor) {

            $usuario = Usuario::find($id);


            if ($request->password) {
                //$password = bcrypt($request->password);
                //$usuario->update("password"->$password);
                $request->password = bcrypt($request->password);
                $usuario->update(array_merge(
                    $request->all(),
                    ['password' => bcrypt($request->password)]
                ));
            } else {
                $usuario->update($request->all());
            }

            unset($usuario['password']);
            unset($usuario['codigo_verificacion	']);

            if ($usuario->id_alumno) {
                $alumno = Alumno::find($usuario->id_alumno);
                $alumno->update($request->all());
                $respuesta = response()->json([
                    'message' => 'Actualizado con éxito',
                    'user' => [$usuario, $alumno]
                ], 200);
            } else {
                $profesor = Profesor::find($usuario->id_profesor);
                $profesor->update($request->all());
                unset($profesor['es_admin']);
                $respuesta = response()->json([
                    'message' => 'Actualizado con éxito',
                    'user' => [$usuario, $profesor]
                ], 200);
            }

            return $respuesta;
        } else if (auth()->user()->id == $id) {

            $usuario = Usuario::find(auth()->user()->id);

            if ($request->password) {
                $request->password = bcrypt($request->password);
                $usuario->update(array_merge(
                    $request->all(),
                    ['password' => bcrypt($request->password)]
                ));
            } else {
                $usuario->update($request->all());
            }

            unset($usuario['password']);
            unset($usuario['codigo_verificacion	']);

            if ($usuario->id_alumno) {
                $alumno = Alumno::find($usuario->id_alumno);
                $grupo_origen = $alumno->id_grupo;
                //return $grupo_origen;
                $alumno->update($request->all());
                $alumno->update(['id_grupo' => $grupo_origen]);

                $respuesta = response()->json([
                    'message' => 'Actualizado con éxito',
                    'user' => [$usuario, $alumno]
                ], 200);
            } else {
                $profesor = Profesor::find($usuario->id_profesor);
                $profesor->update($request->all());
                unset($profesor['es_admin']);
                $respuesta = response()->json([
                    'message' => 'Actualizado con éxito',
                    'user' => [$usuario, $profesor]
                ], 200);
            }

            return $respuesta;
        } else {
            return response()->json([
                'error' => 'No autorizado',
            ], 401);
        }
    }
}
