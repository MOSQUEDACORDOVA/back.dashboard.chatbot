<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Importar Mail
use App\Mail\SendUserPassword; // Importar el Mailable para el correo


class AuthController extends Controller
{
    public function checkSession(Request $request)
    {
        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            return response()->json([
                'status' => 'success',
                'message' => 'Token is valid.',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.'
            ], 401);
        }
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            // 'type' => 1,
            'password' => Hash::make($request->password),
        ]);

        // Enviar correo con la contraseña
        try {
            Mail::to($user->email)->send(new SendUserPassword($request->password));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Usuario registrado, pero el correo no pudo enviarse', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Usuario registrado con éxito y correo enviado'], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!auth()->attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                // 'email' => ['Credenciales incorrectas'],
                'email' => ['Invalid credentials, try again.'],
            ]);
        }

        $token = auth()->user()->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer', 'user' => auth()->user()]);
    }
}
