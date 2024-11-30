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
            $user->isEnable = true;
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

    public function registerClient(Request $request)
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
                        $fail('El teléfono móvil ingresado no es válido. Verifique si cuenta con el número de área (+56)');
                    }
                }],
                'email' => 'required|email|unique:users',
            ], $messages);
    
            // Buscar el rol "Cliente" en la base de datos
            $role = Role::where('name', 'Cliente')->first();
    
            if (!$role) {
                return response([
                    'message' => 'El rol "Cliente" no existe',
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
    
            // Crear el usuario y asignar automáticamente el rol "Cliente"
            $user = new User();
            $user->rut = strtoupper($request->rut);
            $user->name = $request->name;
            $user->lastname1 = $request->lastname1;
            $user->lastname2 = $request->lastname2;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->password = strtoupper($request->rut); // La contraseña es el RUT, puedes modificar esto si es necesario
            $user->role_id = $role->id; // Asignar automáticamente el rol "Cliente"
            $user->isEnable = true;
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
    

    public function changePassword(Request $request)
    {
        try {
            // Validaciones
            $messages = [
                'email.required' => 'El correo es requerido',
                'email.email' => 'El correo debe ser válido',
                'email.exists' => 'El correo ingresado no está registrado',
                'new_password.required' => 'La nueva contraseña es requerida',
                'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
            ];
    
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'new_password' => [
                    'required',
                    'string',
                    'min:8',
                    function ($attribute, $value, $fail) {
                        if (!preg_match('/[A-Z]/', $value) || !preg_match('/\d/', $value)) {
                            $fail('La nueva contraseña no cumple con los estándares de seguridad');
                        }
                    },
                ],
            ], $messages);
    
            // Buscar el usuario
            $user = User::where('email', $request->email)->first();
    
            // Actualizar la contraseña
            $user->password = bcrypt($request->new_password);
            $user->save();
    
            return response([
                'message' => 'Contraseña actualizada con éxito',
                'data' => [],
                'error' => false,
            ], 200);
    
        } catch (\Exception $e) {
            return response([
                'message' => 'Error al cambiar la contraseña',
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function disableUser(Request $request)
    {
        $request->validate(['rut' => 'required|string']);
    
        $user = User::where('rut', $request->rut)->first();
    
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    
        if (!$user->isEnable) {
            return response()->json(['message' => 'El usuario ya está deshabilitado'], 400);
        }
    
        $user->isEnable = false;
        $user->save();
    
        return response()->json(['message' => 'Usuario deshabilitado exitosamente'], 200);
    }
    
    public function enableUser(Request $request)
    {
        $request->validate(['rut' => 'required|string']);
    
        $user = User::where('rut', $request->rut)->first();
    
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    
        if ($user->isEnable) {
            return response()->json(['message' => 'El usuario ya está habilitado'], 400);
        }
    
        $user->isEnable = true;
        $user->save();
    
        return response()->json(['message' => 'Usuario habilitado exitosamente'], 200);
    }
    

    public function manageCustomers(Request $request)
{
    try {    
        $page = $request->has('page') ? $request->page : 1;
        $perPage = $request->has('per_page') ? $request->per_page : 10; 

        // Obtener la lista de clientes con paginación
        $customers = User::select('name', 'lastname1', 'lastname2', 'rut', 'phone', 'email')
            ->where('role_id', 1) // Se asegura que solo sean clientes
            ->paginate($perPage, ['*'], 'page', $page);

        if ($customers->isEmpty()) {
            return response([
                'message' => 'No hay clientes registrados',
                'data' => [],
                'error' => false,
            ], 200);
        }

        return response([
            'message' => 'Clientes obtenidos con éxito',
            'data' => $customers->items(),
            'error' => false,
        ], 200);

    } catch (\Exception $e) {
        return response([
            'message' => 'Error al obtener la lista de clientes',
            'data' => [],
            'error' => $e->getMessage(),
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
