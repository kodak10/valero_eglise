<?php

// app/Http/Controllers/ParticipationController.php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ParticipationController extends Controller
{
    public function show($slug)
    {
        $evenement = Evenement::where('nom', 'like', str_replace('-', ' ', $slug))->firstOrFail();

        $cookieToken = Cookie::get('participation_token');
        $presence = null;

        if ($cookieToken) {
            $presence = Presence::where('cookie_token', $cookieToken)
                ->where('evenement_id', $evenement->id)
                ->first();
        }

        return view('pages.participation.form', compact('evenement', 'presence'));
    }

    public function submit(Request $request, $evenement_id)
    {
        $evenement = Evenement::findOrFail($evenement_id);

        $cookieToken = Cookie::get('participation_token');

        // Vérifier le nombre de scans
        $scanCount = Presence::where('cookie_token', $cookieToken)
            ->where('evenement_id', $evenement->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($scanCount >= $evenement->nombre_scan_max) {
            return redirect()->back()->with('error', 'Nombre de scans maximum atteint pour aujourd\'hui.');
        }

        // Validation conditionnelle
        $rules = [
            'nombre_enfants' => 'required|integer|min:0',
            'nombre_invites' => 'required|integer|min:0',
        ];

        if (!$cookieToken) {
            $rules += [
                'nom' => 'required|string',
                'prenoms' => 'required|string',
                'contact' => 'required|string',
                'classe_metho' => 'required|string',
                'statut' => 'required|in:membre,invite',
            ];

            if ($request->statut === 'membre') {
                $rules['structures'] = 'required|array|min:1';
            }
        }

        $data = $request->validate($rules);

        if (!$cookieToken) {
            $cookieToken = Str::uuid();
        }

        Presence::create([
            'evenement_id' => $evenement->id,
            'nom' => $request->nom ?? '',
            'prenoms' => $request->prenoms ?? '',
            'contact' => $request->contact ?? '',
            'classe_metho' => $request->classe_metho ?? '',
            'est_invite' => $request->statut === 'invite',
            'structures' => $request->structures ?? [],
            'nombre_enfants' => $request->nombre_enfants,
            'nombre_invites' => $request->nombre_invites,
            'cookie_token' => $cookieToken,
        ]);

        // return redirect()->back()
        //     ->withCookie(cookie()->forever('participation_token', $cookieToken))
        //     ->with('success', 'Présence enregistrée avec succès.');

        return response()
    ->view('pages.participation.success', ['evenement' => $evenement])
    ->withCookie(cookie()->forever('participation_token', $cookieToken));

    }
}
