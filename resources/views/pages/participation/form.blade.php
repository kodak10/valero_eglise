@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <h2>Participation à l’événement : {{ $evenement->nom }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('participation.submit', $evenement->id) }}" method="POST">
        @csrf

        @if(!$presence)
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
        @else
            <p>Bonjour {{ $presence->prenoms }} {{ $presence->nom }}, bon dimanche !</p>
        @endif

        <div class="mb-3">
            <label>Nombre d’enfants présents</label>
            <input type="number" name="nombre_enfants" class="form-control" value="0" required>
        </div>

        <div class="mb-3">
            <label>Nombre d’invités présents</label>
            <input type="number" name="nombre_invites" class="form-control" value="0" required>
        </div>

        <button class="btn btn-primary">Valider</button>
    </form>
</div>

<script>
    const statutRadios = document.getElementsByName('statut');
    const structuresDiv = document.getElementById('structures');

    statutRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            structuresDiv.style.display = this.value === 'membre' ? 'block' : 'none';
        });
    });
</script>
@endsection
