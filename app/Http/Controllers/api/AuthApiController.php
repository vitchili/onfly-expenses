<?php 
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthApiController extends Controller
{

    /**
     * Autentica o usuário na API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): mixed
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::find(auth()->user()->id);
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        throw ValidationException::withMessages([
            'email' => "As credenciais estão incorretas.",
        ]);
    }

    /**
     * Cria um novo usuário na API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): string
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    /**
     * Faz o logout do usuário autenticado na API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): mixed
    {
        $request->user()->tokens()->delete();
        Auth::guard('web')->logout();
        return response()->json(['message' => 'Logout efetuado com sucesso.'], 200);
    }

}