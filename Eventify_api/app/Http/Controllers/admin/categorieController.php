<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{

    public function create(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
            ], [
            'libelle.required' => 'Le libellé de la catégorie est requis.',
            ]);
            if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
            }

            $categorie = new Categorie();
            $categorie->libelle = $request->input('libelle');
            $categorie->save();

            return response()->json([
            'status' => true,
            'message' => 'Catégorie créée avec succès',
            'categorie' => $categorie
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
            'status' => false,
            'message' => 'Une erreur est survenue lors de la création de la catégorie.',
            'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
    $categories = Categorie::all();
    return response()->json([
        'status' => true,
        'categories' => $categories
    ], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $categorie = Categorie::findOrFail($id);

            $validator = \Validator::make($request->all(), [
                'libelle' => 'required|string|max:255',
            ], [
                'libelle.required' => 'Le libellé de la catégorie est requis.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $categorie->libelle = $request->input('libelle');
            $categorie->save();

            return response()->json([
                'status' => true,
                'message' => 'Catégorie mise à jour avec succès',
                'categorie' => $categorie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            $categorie->delete();

            return response()->json([
                'status' => true,
                'message' => 'Catégorie supprimée avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Une erreur est survenue lors de la suppression de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
