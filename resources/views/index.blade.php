@extends('layouts.app')

@section('content')
    <script src="{{ asset('js/filtre.js') }}"></script>

    <section id="presentation" class="section-projets">
            <h2>Hey, je suis Nathanaël Rasoamanana.</h2>
            <p>Étudiant en <strong>Master MIASHS - Informatique et Cognition </strong> à l'Université de Grenoble Alpes.</p>
            <p>Je développe une expertise principalement orientée vers le <strong>développement informatique et web</strong>, tout en gardant une ouverture vers les <strong>sciences cognitives et comportementales.</strong></p>
    </section>

    <section id="projets" class="section-projets">

        <div class="projet-filters">
            <button class="filter-btn active" data-filter="all">Tous</button>
            @foreach($technoList as $techno)
                <button class="filter-btn" data-filter="{{ strtolower($techno) }}">{{ $techno }}</button>
            @endforeach
        </div>

        <!-- Container des projets -->
        <div class="container">

            @foreach($projets as $projet)
                @php
                    $techs = is_array($projet->technologies)
                        ? $projet->technologies
                        : json_decode($projet->technologies ?? '[]', true);
                @endphp

                <div class="projet-card"
                    data-techno="{{ implode(' ', array_map('strtolower', $techs)) }}">
                    <h3>{{ $projet->nom }}</h3>
                    <p>Projet : {{ $projet->type }}</p>
                    <p class="resume">{{ $projet->resume }}</p>

                    @if(!empty($techs))
                        <div class="techno-list">
                            @foreach($techs as $techno)
                                <span class="techno-badge">{{ $techno }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- <a href="{{ route('projets.show', $projet) }}">Voir</a> --}}
                    <a href="{{ route('projets.show', ['projet' => $projet->id]) }}">Voir</a>

                </div>
            @endforeach
        </div>
    </section>
@endsection
