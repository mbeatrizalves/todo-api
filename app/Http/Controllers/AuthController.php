<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ], [
            'name.required'      => 'O campo nome é obrigatório.',
            'email.required'     => 'O campo e-mail é obrigatório.',
            'email.email'        => 'Informe um e-mail válido.',
            'email.unique'       => 'Este e-mail já está em uso.',
            'password.required'  => 'O campo senha é obrigatório.',
            'password.confirmed' => 'A confirmação de senha não confere.',
            'password.min'       => 'A senha deve ter no mínimo 8 caracteres.',
        ]);

        $user  = User::create($validated);
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse('Usuário cadastrado com sucesso.', [
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'O campo e-mail é obrigatório.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('Credenciais inválidas.', null, 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse('Login realizado com sucesso.', [
            'user'  => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        // Revoga apenas o token atual (não todos os tokens do usuário)
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse('Logout realizado com sucesso.');
    }
}
