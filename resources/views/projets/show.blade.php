@extends('layouts.app')

@section('title', $projet->nom)

@section('content')
<script src="{{ asset('js/techno.js') }}"></script>

    <div class="section-projets">projet-column
<div class="container">

        <!-- Bloc gauche : infos principales + images -->
        <div class="bloc-left">
            <h2>{{ $projet->nom }}</h2>
            <p><strong>Projet :</strong> {{ $projet->type }}</p>

            @if($projet->images->count())
                <div class="image-section-container">
                    <div class="image-section" id="imageCarousel">
                        @foreach($projet->images as $image)
                            <figure class="image-item">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Image du projet">
                                {{-- <img src="{{ asset('storage/projets/' . $image->path) }}" alt="Image du projet"> --}}
                                {{-- <img src="{{ Storage::disk('s3')->url($image->path) }}" alt="Image du projet"> --}}
                                {{-- <img src="{{ asset($image->path) }}" alt="Image du projet"> --}}

                            </figure>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($projet->technologies) && count($projet->technologies))
                <div class="techno-list">
                    @foreach($projet->technologies as $techno)
                        <span class="techno-badge">{{ $techno }}</span>
                    @endforeach
                </div>
            @endif

            <p><strong>Résumé :</strong> {{ $projet->resume }}</p>
        </div>

        <!-- Bloc droit : description détaillée -->
        <div class="bloc-right">
            <h2>Description du projet</h2>

            @if($projet->etapesConception->count())
                <h4>Étapes de conception</h4>
                <ul>
                    @foreach($projet->etapesConception as $etape)
                        <li>
                            @if($etape->titre)
                                <strong>{{ $etape->titre }} :</strong>
                            @endif
                            {{ $etape->description }}
                        </li>
                    @endforeach
                </ul>
            @endif

            @if($projet->etapesDeveloppement->count())
                <h4>Développement</h4>
                <ul>
                    @foreach($projet->etapesDeveloppement as $etape)
                        <li>
                            @if($etape->titre)
                                <strong>{{ $etape->titre }} :</strong>
                            @endif
                            {{ $etape->description }}
                        </li>
                    @endforeach
                </ul>
            @endif

            @if($projet->description_difficultes)
                <h4>Difficultés</h4>
                <p>{{ $projet->description_difficultes }}</p>
            @endif

            @if($projet->apport_personnel)
                <h4>Ce que j'ai appris</h4>
                <p>{{ $projet->apport_personnel }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
