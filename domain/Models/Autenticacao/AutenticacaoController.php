<?php

namespace Api\Models\Autenticacao;

use Api\Models\Autenticacao\Actions\LoginAction;
use Api\Models\Autenticacao\Actions\RegisterAction;
use Api\Models\Autenticacao\Requests\LoginRequest;
use Api\Models\Autenticacao\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AutenticacaoController extends Controller
{
    /**
     * Login
     *
     * @param LoginRequest $request
     * @param LoginAction $action
     * @return JsonResponse
     * @unauthenticated
     */
    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        $row = $action->handle($request->validated());

        return response()->json(data: ['token' => $row]);
    }

    /**
     * Register
     *
     * @param RegisterRequest $request
     * @param RegisterAction $action
     * @return JsonResponse
     * @unauthenticated
     */
    public function register(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        $row = $action->handle($request->validated());

        return response()->json(data: ['token' => $row]);
    }
}
