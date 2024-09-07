<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth;
use Throwable;

class AuthController extends Controller
{
    
    /**
     * Login
     *
     * This method handles the login functionality for users.
     *
     * @param LoginRequest $request The login request object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the login result.
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('identity_number', 'password');
            if (!auth()->attempt($credentials)) {
                return apiResponse(
                    false,
                    'Invalid credentials',
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                );
            }
            
            $user= User::where('identity_number',$request->identity_number)->first();
            
            return $this->respondWithToken($user, 'You logged in successfully');

        } catch (Throwable $th) {
            return failResponse($th);
        }
    }

    /**
     * Register
     *
     * This method handles the Register functionality for users.
     * @param CreateUserRequest $request The UserRegister request object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the UserRegister result.
     */
    public function register(CreateUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'identity_number' => $request->identity_number,
                'password' => Hash::make($request->password),
            ]);

            if($user)
            {
                return $this->respondWithToken($user, 'User Registered Successfully');
            }
        } catch (Throwable $th) {
            return failResponse($th);
        }
    }


    /**
     * Get the token array structure.
     *
     * This method constructs the token array structure for the JSON response.
     *
     * @param  $user The authenticated user object.
     * @param  $message The success message.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the token structure.
     */
    protected function respondWithToken($user, $message)
    {
        $data = [
            'user' => new UserResource($user),
            'token' => auth()->guard('api')->login($user),
        ];

        return apiResponse(
            true,
            $message,
            Response::HTTP_OK,
            $data
        );
    }
}
