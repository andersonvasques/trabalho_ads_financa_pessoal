<?php

namespace Api\Models\Autenticacao\Actions;

use Api\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class LoginAction
{
    /**
     * Realiza o login de um usuário e retorna um token de autenticação.
     *
     * @param array $dados
     * @return string
     */
    public function handle(array $dados): string
    {
        $email    = $dados['email'] ?? null;
        $password = $dados['password'] ?? null;

        throw_if(
            condition: !Auth::attempt(credentials: ['email' => $email, 'password' => $password]),
            exception: ValidationException::withMessages([
                'email' => __('auth.failed'),
            ])
        );

        /**
         * @var User $user
         */
        $user  = Auth::user();
        $token = $user->createToken(name: 'api-token')->plainTextToken;

        return $token;
    }
}
