@extends('layouts.master')

@section('content')
<div class="container-xl mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Liste des participants</h3>
        <!-- Bouton pour ouvrir le modal -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#participationModal">
            + Ajouter une présence
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Participants</h3>
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

<!-- Modal de participation -->
<div class="modal fade" id="participationModal" tabindex="-1" aria-labelledby="participationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('participation.add') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="participationModalLabel">Ajouter une participation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="mb-3">
                        <label for="evenement_id">Événement</label>
                        <select name="evenement_id" class="form-control" required>
                            <option value="">-- Sélectionner un événement --</option>
                            @foreach($evenements as $event)
                                <option value="{{ $event->id }}">{{ $event->nom }} - {{ \Carbon\Carbon::parse($event->date_evenement)->format('d/m/Y H:i') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Prénoms</label>
                        <input type="text" name="prenoms" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Contact</label>
                        <input type="text" name="contact" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Classe méthodiste</label>
                        <input type="text" name="classe_metho" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Statut</label><br>
                        <input type="radio" name="statut" value="invite" checked> Invité
                        <input type="radio" name="statut" value="membre"> Membre
                    </div>

                    <div class="mb-3" id="structures" style="display:none;">
                        <label>Structures d’appartenance</label><br>
                        @php
                            $structures = ['Jeunesse', 'Chorale', 'Groupe musical', 'Intercession', 'Finance', 'Communication', 'Mouvement réveil', 'Conseiller', 'Collège des prédicateurs', 'Moniteur', 'Organisation', 'Technicien son'];
                        @endphp
                        @foreach($structures as $structure)
                            <label><input type="checkbox" name="structures[]" value="{{ $structure }}"> {{ $structure }}</label><br>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label>Nombre d’enfants présents</label>
                        <input type="number" name="nombre_enfants" class="form-control" value="0" required>
                    </div>

                    <div class="mb-3">
                        <label>Nombre d’invités présents</label>
                        <input type="number" name="nombre_invites" class="form-control" value="0" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS pour statut -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statutRadios = document.getElementsByName('statut');
        const structuresDiv = document.getElementById('structures');

        statutRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                structuresDiv.style.display = this.value === 'membre' ? 'block' : 'none';
            });
        });
    });
</script>
@endsection
