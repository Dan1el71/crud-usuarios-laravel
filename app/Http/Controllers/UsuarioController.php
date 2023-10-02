<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function getUsuarios(){
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    public function createUsuario(Request $request){
        try{
            $validarUsuario = Validator::make(
                $request->all(),
                [
                    'nombre' => 'required',
                    'correo' => 'required|email|unique:usuarios,correo',
                    'telefono' => 'required|unique:usuarios,telefono|max:15'
                ]);

            if ($validarUsuario->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validarUsuario->errors()->all()
                ], 400);
            }

            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'telefono' => $request->telefono
            ]);

            return response()->json([
                'status'=>true,
                'message' => 'Usuario creado correctamente'
            ],200);

        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
