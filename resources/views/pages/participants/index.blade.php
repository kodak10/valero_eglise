@extends('layouts.master')

@section('content')
<div class="container-xl mt-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des participants</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-vcenter">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénoms</th>
                        <th>Contact</th>
                        <th>Classe Métho</th>
                        <th>Est invité ?</th>
                        <th>Structures</th>
                        <th>Nb Enfants</th>
                        <th>Nb Invités</th>
                        <th>Événement</th>
                        <th>Date d’inscription</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participants as $participant)
                        <tr>
                            <td>{{ $participant->nom }}</td>
                            <td>{{ $participant->prenoms }}</td>
                            <td>{{ $participant->contact }}</td>
                            <td>{{ $participant->classe_metho }}</td>
                            <td>{{ $participant->est_invite ? 'Oui' : 'Non' }}</td>
                            <td>
                                @if($participant->structures)
                                    @foreach(json_decode($participant->structures) as $structure)
                                        <span class="badge bg-primary">{{ $structure }}</span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $participant->nombre_enfants }}</td>
                            <td>{{ $participant->nombre_invites }}</td>
                            <td>{{ $participant->evenement->nom ?? 'N/A' }}</td>
                            <td>{{ $participant->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
