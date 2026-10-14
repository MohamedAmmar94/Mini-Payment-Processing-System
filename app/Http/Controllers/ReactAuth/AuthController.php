<?php
namespace App\Http\Controllers\ReactAuth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
    public function login(Request $request) {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $request->session()->regenerate();
        // dd($request->user('web'));
        return response()->json([
            'message' => 'Login successful.',
            'user'    => UserResource::make(
                $request->user('web')
            ),
        ]);
    }

    public function me(Request $request) {
        return response()->json([
            'user' => UserResource::make(
                $request->user('web')
            ),
        ]);
    }

    public function logout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout successful.',
        ]);
    }
}
