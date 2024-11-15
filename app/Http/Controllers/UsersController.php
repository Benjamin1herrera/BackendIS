<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function register(Request $request)
    {
        try {

            $messages = $this->makeMessage();
            $request->validate([
                'rut' => ['required', 'string', 'min:8', 'max:9', function ($attribute, $value, $fail) {
                    if (!$this->validarRut($value)) {
                        $fail('El RUT ingresado no es válido');
                    }
                }],
                'name' => 'required|string|min:3|max:255',
                'lastname1' => 'required|string|min:3|max:255',
                'lastname2' => 'required|string|min:3|max:255',
                'phone' => ['required', 'string', function ($attribute, $value, $fail) {
                    // Expresión regular para validar el formato +56 y 9 dígitos
                    if (!preg_match('/^\+56\d{9}$/', $value)) {
                        $fail('El teléfono móvil ingresado no es válido , Verifique si cuenta con el numero de area (+56)');
                    }
                }],
                'email' => 'required|email|unique:users',
                'role' => 'required|string'
            ], $messages);
            

            $roleValidate = Role::where('name', $request->role)->first();

            if (!$roleValidate) {
                return response([
                    'message' => 'El rol no existe',
                    'data' => [],
                    'error' => true
                ], 400);
            }

            if (User::where('email', $request->email)->exists()) {
                return response([
                    'message' => 'El correo ya está registrado',
                    'data' => [],
                    'error' => true
                ], 400);
            }

            $user = new User();
            $user->rut = strtoupper($request->rut);
            $user->name = $request->name;
            $user->lastname1 = $request->lastname1;
            $user->lastname2 = $request->lastname2;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->password = strtoupper($request->rut);  // La contraseña es el RUT, puedes modificar esto si es necesario
            $user->role_id = $roleValidate->id;
            $user->save();

            return response([
                'message' => 'Usuario registrado',
                'data' => [$user],
                'error' => false
            ], 201);

        } catch (\Exception $e) {
            return response([
                'message' => 'Error al registrar el usuario',
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAll()
    {
        try {
            $users = User::all();
            return response([
                'message' => 'Usuarios obtenidos',
                'data' => $users,
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => 'Error al obtener los usuarios',
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $userId = $request->id;

            $user = User::where('id', $userId)->first();
            if (!$user) {
                return response([
                    'message' => 'El usuario no existe',
                    'data' => [],
                    'error' => true
                ], 400);
            }
            $user->delete();

            return response([
                'message' => 'Usuario eliminado',
                'data' => [],
                'error' => false
            ], 200);

        } catch (\Exception $e) {
            return response([
                'message' => 'Error al eliminar el usuario',
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    function makeMessage()
    {
        $messages = [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser un texto',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'name.max' => 'El nombre debe tener como máximo 255 caracteres',
            'email.required' => 'El correo es requerido',
            'email.regex' => 'El correo debe ser un adecuado al formato ejemplo@ejemplo.com',
            'email.unique' => 'El correo ya está registrado',
            'password.required' => 'La contraseña es requerida',
            'password.string' => 'La contraseña debe ser un texto',
            'role.required' => 'El rol es requerido',
            'role.string' => 'El rol debe ser un texto',
        ];
        return $messages;
    }

    function validarRut($rut)
    {
        // Eliminar puntos y guion
        $rut = str_replace(['.', '-'], '', $rut);

        // Verificar que el RUT tenga una longitud válida
        if (strlen($rut) < 8 || strlen($rut) > 9) {
            return false;
        }
        // Separar el número y el dígito verificador
        $numero = substr($rut, 0, -1);
        $dv = strtoupper(substr($rut, -1));

        // Verificar que el dígito verificador sea válido
        if (!preg_match('/^[0-9K]$/', $dv)) {
            return false;
        }

        // Validación del cálculo del dígito verificador
        $sum = 0;
        $factor = 2;

        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $sum += (int)$numero[$i] * $factor;
            $factor = $factor == 7 ? 2 : $factor + 1;
        }

        $mod = $sum % 11;
        $calculatedDv = 11 - $mod;

        if ($calculatedDv == 11) {
            $calculatedDv = 0;
        } elseif ($calculatedDv == 10) {
            $calculatedDv = 'K';
        }
        return $dv == $calculatedDv;
    }
}
