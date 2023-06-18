<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    private array $roleDataHidding = ['created_at', 'updated_at', 'pivot', 'guard_name'];

    /**
     * Register a new user.
     * @OA\Post(
     *      path="/api/register",
     *      summary="Register a new user",
     *      tags={"Auth"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          required={"name","email","password"},
     *          @OA\Property(property="name", type="string", format="string"),
     *          @OA\Property(property="email", type="string", format="string"),
     *          @OA\Property(property="password", type="string", format="string" ),
     *          @OA\Property(property="password_confirmation", type="string", format="string" ),
     *                    ),
     *              ),
     *      @OA\Response(
     *          response=200,
     *          description="User register successfully"  
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="bad request"  
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="An error has occurred"
     *      )
     * )
     */


    public function register(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed'
            ]);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            $user->assignRole(2)->save();

            $user->roles->makeHidden($this->roleDataHidding);

            return okResponse200($user, "User register successfully");
        } catch (\Throwable $th) {
            return badRequestResponse400();
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;

            return response()->json(["success" => true, "data" => $user, "token" => $token, "message" => "Login successfully"]);
        } else {
            return response()->json(errorResponse("Invalid Credentials"), 401);
        }
    }
}
