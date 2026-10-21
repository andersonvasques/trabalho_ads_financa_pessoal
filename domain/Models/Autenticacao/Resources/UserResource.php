<?php

namespace Api\Models\Autenticacao\Resources;

use Api\Models\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name'  => $this->name,
            'email' => $this->email,
        ];
    }
}
