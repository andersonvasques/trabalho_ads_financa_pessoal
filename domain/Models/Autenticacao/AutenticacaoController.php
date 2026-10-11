<?php

namespace Api\Models\Autenticacao;

use Api\Models\Autenticacao\Actions\LoginAction;
use Api\Models\Autenticacao\Actions\LogoutAction;
use Api\Models\Autenticacao\Actions\RegisterAction;
use Api\Models\Autenticacao\Requests\LoginRequest;
use Api\Models\Autenticacao\Requests\RegisterRequest;
use Api\Models\Autenticacao\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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

    /**
     * Logout
     *
     * @param Request $request
     * @param LogoutAction $action
     * @return JsonResponse
     */
    public function logout(Request $request, LogoutAction $action): JsonResponse
    {
        $user = $request->user();

        $action->handle($user);

        return response()->json(data: ['message' => __('auth.logout')]);
    }

    /**
     * Me
     *
     * @param Request $request
     * @return JsonResource
     */
    public function me(Request $request): JsonResource
    {
        $user = $request->user();

        return new UserResource($user);
    }
}
