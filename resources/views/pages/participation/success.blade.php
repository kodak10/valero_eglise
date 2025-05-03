@extends('layouts.master-form')

@section('content')
<div class="container mt-5 text-center">
    <img src="{{ asset('assets/success.webp') }}" alt="Succès" style="max-width: 200px;" class="mb-4">

    <h3 class="text-success">Votre enregistrement a été effectué avec succès !</h3>
    <p class="mt-3">Merci pour votre participation à l’événement <strong>{{ $evenement->nom }}</strong>.</p>
</div>
@endsection
