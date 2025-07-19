<?php

namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ], [
                'nom.required' => 'Veuillez entrer votre nom',
                'prenom.required' => 'Veuillez entrer votre prénom',
                'email.required' => 'Veuillez entrer votre adresse email',
                'email.email' => 'Veuillez entrer un email valide.',
                'email.unique' => 'Cet email est déjà utilisé.',
                'password.required' => 'Le mot de passe est requis.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = new User();
            $user->nom = $request->input('nom');
            $user->prenom = $request->input('prenom');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            auth()->login($user);
            return response()->json([
                'status' => true,
                'message' => 'Bienvenue sur la plateforme eventify',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ], [
                'email.required' => 'Veuillez entrer votre adresse email',
                'email.email' => 'Veuillez entrer un email valide.',
                'password.required' => 'Le mot de passe est requis.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $credentials = $request->only('email', 'password');

            if (auth()->attempt($credentials)) {
                $user = auth()->user();
                return response()->json([
                    'status' => true,
                    'message' => 'Heureux de vous revoir',
                    'user' => $user
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Identifiants invalides'
            ], 401);
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
        auth()->logout();
        return response()->json([
            'status' => true,
            'message' => 'Déconnexion réussie.'
        ]);
    }
}
