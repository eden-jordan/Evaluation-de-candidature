<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evenement;
use Illuminate\Support\Facades\Validator;

class evenementController extends Controller
{

    public function create()
    {
    try {
        $request = request();
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'lieu' => 'required|string|max:255',
            'id_categorie' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evenement = Evenement::create([
            'titre' => $request->input('titre'),
            'description' => $request->input('description'),
            'date' => $request->input('date'),
            'lieu' => $request->input('lieu'),
            'id_categorie' => $request->input('id_categorie'),
        ]);

        return response()->json([
            'status' => true,
            'evenement' => $evenement
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Erreur lors de la création de l\'événement.',
            'error' => $e->getMessage()
        ], 500);
    }
    }

     public function index()
    {
        $evenements = Evenement::all();
        return response()->json([
            'status' => true,
            'evenements' => $evenements
        ], 200);
    }

}
