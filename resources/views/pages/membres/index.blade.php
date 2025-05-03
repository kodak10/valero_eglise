@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Membres</h2>
            </div>
            
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @foreach($membres as $membre)
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3 rounded" style="background-image: url({{ Storage::url($membre->image ?? 'avatars/default-avatar.png') }})"></span>
                            <h3 class="m-0 mb-1"><a href="#">{{ $membre->nom }}</a></h3>
                            <div class="text-secondary">{{ $membre->prenoms }}</div>
                            <div class="mt-3">
                                    <span class="badge bg-purple-lt">{{ $membre->classe_metho }}</span>
                            </div>
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>

        
    </div>
</div>



@endsection
