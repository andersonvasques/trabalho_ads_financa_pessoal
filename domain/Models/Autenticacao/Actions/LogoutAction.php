<?php

namespace Api\Models\Autenticacao\Actions;

use Api\Models\User\User;
use Laravel\Sanctum\PersonalAccessToken;

final class LogoutAction
{
    /**
     * Desloga o usuário atual, removendo o token de acesso atual.
     *
     * @param User $user
     * @return void
     */
    public function handle(User $user): void
    {
        /**
         * @var PersonalAccessToken $token
         */
        $token = $user->currentAccessToken();

        $token->delete();
    }
}
