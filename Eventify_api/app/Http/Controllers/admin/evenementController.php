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
            'heure' => 'required|string|max:255',
            'lieu' => 'required|string|max:255',
            'id_categorie' => 'required|integer|exists:categories,id',
        ], [
            'titre.required' => 'Le titre est requis.',
            'description.required' => 'La description est requise.',
            'date.required' => 'La date est requise.',
            'heure.required' => 'L\'heure est requise.',
            'lieu.required' => 'Le lieu est requis.',
            'id_categorie.required' => 'La catégorie est requise.',
            'id_categorie.exists' => 'La catégorie sélectionnée n\'existe pas.'
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
            'heure' => $request->input('heure'),
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

    public function update(Request $request, $id)
    {
        try {
            $evenement = Evenement::find($id);
            if (!$evenement) {
                return response()->json([
                    'status' => false,
                    'message' => 'Événement non trouvé.'
                ], 404);
            }
            $validator = Validator::make($request->all(), [
                'titre' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'date' => 'sometimes|required|date',
                'heure' => 'sometimes|required|string|max:255',
                'lieu' => 'sometimes|required|string|max:255',
                'id_categorie' => 'sometimes|required|integer|exists:categories,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $evenement->update($request->all());

            return response()->json([
                'status' => true,
                'evenement' => $evenement
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la mise à jour de l\'événement.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $evenement = Evenement::find($id);
            if (!$evenement) {
                return response()->json([
                    'status' => false,
                    'message' => 'Événement non trouvé.'
                ], 404);
            }

            $evenement->delete();

            return response()->json([
                'status' => true,
                'message' => 'Événement supprimé avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la suppression de l\'événement.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
