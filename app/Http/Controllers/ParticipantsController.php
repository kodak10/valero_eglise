<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Presence;
use Illuminate\Http\Request;

class ParticipantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participants = Presence::with('evenement')->get(); // avec relation événement
        $evenements = Evenement::all(); // tu charges la liste des événements

        //$participants = Presence::all();
        return view('pages.participants.index', compact('participants', 'evenements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'evenement_id' => 'required|exists:evenements,id',
        'nom' => 'required',
        'prenoms' => 'required',
        'contact' => 'required',
        'classe_metho' => 'required',
        'statut' => 'required|in:invite,membre',
        'structures' => 'nullable|array',
        'nombre_enfants' => 'required|integer|min:0',
        'nombre_invites' => 'required|integer|min:0',
    ]);

    Presence::create([
        'evenement_id' => $validated['evenement_id'],
        'nom' => $validated['nom'],
        'prenoms' => $validated['prenoms'],
        'contact' => $validated['contact'],
        'classe_metho' => $validated['classe_metho'],
        'est_invite' => $validated['statut'] === 'invite',
        'structures' => $validated['statut'] === 'membre' ? json_encode($validated['structures'] ?? []) : null,
        'nombre_enfants' => $validated['nombre_enfants'],
        'nombre_invites' => $validated['nombre_invites'],
    ]);

    return redirect()->back()->with('success', 'Participation enregistrée avec succès.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
