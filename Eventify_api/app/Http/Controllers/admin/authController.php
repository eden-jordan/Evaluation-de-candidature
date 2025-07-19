<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ], [
                'email.required' => 'Veuillez entrer votre email.',
                'email.email' => 'Veuillez entrer un email valide.',
                'password.required' => 'Veuillez entrer votre mot de passe.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->input('email'))
                        ->where('role', 'admin')
                        ->first();

            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Identifiants incorrects.'
                ], 401);
            }


            $token = $user->createToken('admin-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Connexion réussie.',
                'user' => $user,
                'token' => $token
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Déconnexion réussie.'
        ]);
    }
}
