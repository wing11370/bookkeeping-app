<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class UserController extends BaseController
{

    public function __construct(protected User $user, protected UserService $userService)
    {
    }
    /**
     * @OA\POST(
     *      path="/api/register",
     *      operationId="register",
     *      tags={"使用者"},
     *      summary="註冊會員",
     *      description="註冊會員",
     *     @OA\RequestBody(
     *     description="註冊會員",
     *     required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", example="test"),
     *              @OA\Property(property="email", type="string", example="test@test.com"),
     *              @OA\Property(property="password", type="string", example="test1234"),
     *          ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="註冊成功",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", example="token"),
     *          )
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="註冊失敗",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="xxxx"),
     *          )
     *      ),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8|max:255',
            ]);
            return response()->json([
                'token' => $this->userService->register([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password
                ])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\POST(
     *     path="/api/login",
     *     operationId="login",
     *     tags={"使用者"},
     *     summary="登入",
     *     description="登入",
     *     @OA\RequestBody(
     *          description="登入",
     *          required=true,
     *              @OA\JsonContent(
     *                  required={"email","password"},
     *                  @OA\Property(property="email", type="string", example="test@test.com"),
     *                  @OA\Property(property="password", type="string", example="test1234"),
     *              ),
     *          ),
     *     @OA\Response(
     *          response=200,
     *          description="登入成功",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", example="token"),
     *          )
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="登入失敗",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="xxxx"),
     *          )
     *     ),
     * )
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        try {
            return response()->json([
                'token' => $this->userService->login([
                    'email' => $request->email,
                    'password' => $request->password
                ])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

}
