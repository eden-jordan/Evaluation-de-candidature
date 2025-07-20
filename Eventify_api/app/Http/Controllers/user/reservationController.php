<?php

namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Validator;

class reservationController extends Controller
{
    public function create()
    {
        try {
            $request = request();
            $user = auth()->user();

            $validator = Validator::make($request->all(), [
                'id_evenement' => 'required|integer|exists:evenements,id',
            ], [
                'id_evenement.required' => 'L\'ID de l\'événement est requis.',
                'id_evenement.exists' => 'L\'événement sélectionné n\'existe pas.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $reservation = Reservation::create([
                'numero' => 'num' . $request->input('id'),
                'id_utilisateur' => $request->input('id_utilisateur', optional($user)->id),
                'id_evenement' => $request->input('id_evenement'),
            ]);

            return response()->json([
                'status' => true,
                'reservation' => $reservation
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la création de la réservation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $user = auth()->user();
        $reservations = Reservation::where('id_utilisateur', optional($user)->id)->get();

        return response()->json([
            'status' => true,
            'reservations' => $reservations
        ], 200);
    }

    public function delete($id)
    {
        try {
            $user = auth()->user();
            $reservation = Reservation::where('id_utilisateur', optional($user)->id)
                ->where('id', $id)
                ->first();

            if (!$reservation) {
                return response()->json([
                    'status' => false,
                    'message' => 'Reservation non trouvée.'
                ], 404);
            }

            $reservation->delete();

            return response()->json([
                'status' => true,
                'message' => 'Reservation annulée avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de l\'annulation de la reservation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
