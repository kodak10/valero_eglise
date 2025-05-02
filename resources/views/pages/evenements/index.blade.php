@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Gestion des Événements</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal">
                    + Ajouter un événement
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Messages de validation ou de succès/erreur -->
<div class="container-xl mt-3">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste des événements</h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Lieu</th>
                            <th>Limite Scan (h)</th>
                            <th>Scan Max</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evenements as $evenement)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($evenement->date_evenement)->format('d/m/Y H:i') }}</td>
                                <td>{{ $evenement->nom }}</td>
                                <td>{{ $evenement->description }}</td>
                                <td>{{ $evenement->lieu }}</td>
                                <td>{{ $evenement->limite_scan_heure }}h</td>
                                <td>{{ $evenement->nombre_scan_max }}</td>
                                <td>
                                    <div class="btn-group">
                                        @if($evenement->qr_code_path)
                                            <a href="{{ route('evenements.downloadQrCode', $evenement->id) }}" class="btn btn-sm btn-success mt-2">Télécharger</a>
                                        @else
                                            
                                        @endif
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editEventModal{{ $evenement->id }}">
                                            Éditer
                                        </button>
                                        <form action="{{ route('evenements.destroy', $evenement->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    
                                </td>
                                
                            </tr>

                            <!-- Modal Édition -->
                            <div class="modal fade" id="editEventModal{{ $evenement->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('evenements.update', $evenement->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier l’événement</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Nom</label>
                                                    <input type="text" name="nom" value="{{ old('nom', $evenement->nom) }}" class="form-control @error('nom') is-invalid @enderror" required>
                                                    @error('nom')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Date</label>
                                                    <input type="datetime-local" name="date_evenement" value="{{ old('date_evenement', \Carbon\Carbon::parse($evenement->date_evenement)->format('Y-m-d\TH:i')) }}" class="form-control @error('date_evenement') is-invalid @enderror" required>
                                                    @error('date_evenement')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $evenement->description) }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Lieu</label>
                                                    <input type="text" name="lieu" value="{{ old('lieu', $evenement->lieu) }}" class="form-control @error('lieu') is-invalid @enderror">
                                                    @error('lieu')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Scan max</label>
                                                    <input type="number" name="nombre_scan_max" value="{{ old('nombre_scan_max', $evenement->nombre_scan_max) }}" class="form-control @error('nombre_scan_max') is-invalid @enderror" required>
                                                    @error('nombre_scan_max')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Limite heure</label>
                                                    <input type="number" name="limite_scan_heure" value="{{ old('limite_scan_heure', $evenement->limite_scan_heure) }}" class="form-control @error('limite_scan_heure') is-invalid @enderror" required>
                                                    @error('limite_scan_heure')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $evenements->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Création -->
<div class="modal modal-blur fade" id="createEventModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('evenements.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="datetime-local" name="date_evenement" value="{{ old('date_evenement') }}" class="form-control @error('date_evenement') is-invalid @enderror" required>
                        @error('date_evenement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lieu</label>
                        <input type="text" name="lieu" value="{{ old('lieu') }}" class="form-control @error('lieu') is-invalid @enderror">
                        @error('lieu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Scan max</label>
                        <input type="number" name="nombre_scan_max" value="{{ old('nombre_scan_max', 2) }}" class="form-control @error('nombre_scan_max') is-invalid @enderror" required>
                        @error('nombre_scan_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Limite heure</label>
                        <input type="number" name="limite_scan_heure" value="{{ old('limite_scan_heure', 5) }}" class="form-control @error('limite_scan_heure') is-invalid @enderror" required>
                        @error('limite_scan_heure')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
