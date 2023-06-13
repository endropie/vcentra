<?php

namespace App\Http\ApiControllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => ["required", "email", "unique:users,email"],
            'phone' => ["nullable", "unique:users,phone", 'phone'],
            'name' => ["nullable", 'string', 'min:6', 'alpha_dash'],
            'password' => ["required", "min:6"],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = app('hash')->make($request->password);
        $user->save();

        return new UserResource($user);
    }

    public function registerAndLogin(Request $request)
    {
        try {
            $this->register($request);

            $requestLogin = new Request([
                "username" => $request->phone ?? $request->email,
                "password" => $request->password,
            ]);

            return $this->login($requestLogin);
        }
        catch (\Exception $e) {
            throw $e ;
        }

    }

    public function login(Request $request)
    {
        $mode = filter_var($request->get("username"), FILTER_VALIDATE_EMAIL) ? "email" : "phone";

        $this->validate($request, [
            "username" => ["required", "exists:users,$mode"],
            "password" => ["required"],
        ]);

        $request->merge(["$mode" => $request->username]);

        $credentials = request([$mode, "password"]);

        if (auth()->attempt($credentials)) {
            /** @var \App\Models\User $user **/
            $user = auth()->user();
            $expiresAt = \Carbon\Carbon::now()->addSecond()->addDay(1);
            $token = $user->createToken('auth_token', ['*'], $expiresAt);

            return response()->json([
                "access_token" => $token->plainTextToken,
                "token_type" => "bearer",
                "expires_in" => $expiresAt->diffInSeconds() ,

            ]);
        }

        return response()->json(["message" => "Username & password doesnt match"], 401);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user()
    {
        $user = auth()->user();
        $response  = new UserResource($user);
        $response->default(['email', 'phone', 'ability']);
        return $response;
    }

    public function passwordChange(Request $request)
    {
        $this->validate($request, [
            'password' => ["required"],
            'new_password' => ["required", "min:6", "not_in:$request->password"],
            'new_password_confirm' => ["required", "same:new_password"],
        ]);

        /** @var \App\Models\User $user **/
        $user = auth()->user();
        if (app('hash')->check($request->password, $user->password)) {
            $user->password = app('hash')->make($request->get('new_password'));
            $user->save();

            return [
                'message' => 'Password change success.'
            ];
        }

        return response()->json([
            'message' => 'Password change failed.'
        ], 400);
    }
}

