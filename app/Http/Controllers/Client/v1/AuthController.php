<?php

namespace App\Http\Controllers\Client\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**  
 * @OA\Schema(  
 *     schema="LoginRequest",  
 *     required={"email", "password"},  
 *     @OA\Property(property="email", type="string"),  
 *     @OA\Property(property="password", type="string"),  
 * ),  
 */

/**  
 * @OA\Schema(  
 *     schema="UserResponse",  
 *     required={"id", "name", "email"},  
 *     @OA\Property(property="id", type="integer"),  
 *     @OA\Property(property="name", type="string"),  
 *     @OA\Property(property="email", type="string"),  
 * ),  
 */
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
    }


    /**  
     * @OA\Post(  
     *     path="/api/v1/app/login",  
     *     summary="Login user and retrieve JWT token",  
     *     description="This endpoint allows the user to login by providing email and password. A JWT token will be returned upon successful authentication.",  
     *     tags={"Authentication"},  
     *     @OA\RequestBody(  
     *         required=true,  
     *         @OA\JsonContent(  
     *             required={"email", "password"},  
     *             @OA\Property(property="email", type="string", format="email", example="davood@gmail.com"),  
     *             @OA\Property(property="password", type="string", example="12345678"),  
     *         )  
     *     ),  
     *     @OA\Response(  
     *         response=200,  
     *         description="Successfully authenticated",  
     *         @OA\JsonContent(  
     *             @OA\Property(property="token", type="string", example="YOUR_JWT_TOKEN")  
     *         )  
     *     ),  
     *     @OA\Response(  
     *         response=401,  
     *         description="Unauthorized - Invalid credentials",  
     *         @OA\JsonContent(  
     *             @OA\Property(property="error", type="string", example="unauthorized")  
     *         )  
     *     ),  
     *     @OA\Response(  
     *         response=500,  
     *         description="Internal server error - Could not create token",  
     *         @OA\JsonContent(  
     *             @OA\Property(property="error", type="string", example="could_not_create_token")  
     *         )  
     *     ),  
     * )  
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    /**  
     * @OA\Get(  
     *     path="/api/v1/app/me",  
     *     summary="Get authenticated user information",  
     *     security={{"Bearer": {}}},
     *     @OA\Parameter(  
     *        name="Authorization",  
     *        in="header",  
     *        required=true,  
     *        @OA\Schema(type="string", example="Bearer YOUR_JWT_TOKEN")  
     *     ),  
     *     @OA\Response(  
     *         response=200,  
     *         description="User details",  
     *         @OA\JsonContent(ref="#/components/schemas/UserResponse")  
     *     ),  
     *     @OA\Response(  
     *         response=401,  
     *         description="Unauthorized",  
     *     ),  
     * )  
     */
    public function getUser()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
