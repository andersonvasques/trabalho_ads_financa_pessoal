<?php

namespace Api\Models\Autenticacao\Actions;

use Api\Models\User\User;
use Illuminate\Support\Facades\DB;

final class RegisterAction
{
    /**
     * Registra um novo usuário.
     *
     * @param array $dados
     * @return string
     */
    public function handle(array $dados): string
    {
        return DB::transaction(function () use ($dados) {
            $user = User::create([
                'name'     => $dados['name'],
                'email'    => $dados['email'],
                'password' => $dados['password'],
            ]);

            return $user->createToken(name: 'api-token')->plainTextToken;
        });
    }
}
