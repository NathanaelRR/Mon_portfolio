@extends('layouts.app')

@section('content')
    <script src="{{ asset('js/filtre.js') }}"></script>

    <section id="presentation" class="section-presentation">
            <h2>Hey, je suis Nathanaël Rasoamanana.</h2>
            <p>Étudiant en <strong>Master MIASHS - Informatique et Cognition </strong> à l'Université de Grenoble Alpes.</p>
            <p>Je développe une expertise principalement orientée vers le <strong>développement informatique et web</strong>, tout en gardant une ouverture vers les <strong>sciences cognitives et comportementales.</strong></p>
    </section>

     <section id="bienvenue" class="section-bienvenue">
        <h4> BIENVENUE SUR MON PETIT PORTFOLIO </h4>
    </section>

    <section id="profil" class="section-projets">

            <div class="col" id="div1">
                <h3>Engagements & Bénévolats</h3>
                <ul class="engagements">
                <li>
                    <strong>Entraîneur de basket</strong><br>
                    Sporting Club Gières <em>(Aujourd’hui)</em>
                </li>
                <li>
                    <strong>Inclusion numérique</strong><br>
                    Emmaüs Connect Grenoble <em>(Février 2024 - Septembre 2025)</em>
                </li>
                <li>
                    <strong>Distribution alimentaire</strong><br>
                    Comité Solidarité Étudiante – Lyon <em>(Mars 2023 - Juin 2023)</em>
                </li>
                <li>
                    <strong>Aide pédagogique</strong><br>
                    Entraide Scolaire Amicale – Lyon <em>(Mai 2023 - Août 2023)</em>
                </li>
                </ul>
            </div>
            <div class="col" id="div2">
                <h3>Expériences Professionnelles</h3>
                <ul class="experiences">
                <li>
                    <strong>Fundraising – Médecins Sans Frontières</strong><br>
                    Besançon <em>(Juin 2025)</em><br>
                    Collecte de fonds et sensibilisation du public, développement de compétences en communication et persuasion.
                </li>
                <li>
                    <strong>Employé polyvalent – KFC</strong><br>
                    Saint-Priest (Lyon) <em>(Juillet - Août 2021)</em><br>
                    Travail en équipe et gestion des commandes clients.
                </li>
                </ul>
            </div>
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

                    <a href="{{ route('projets.show', $projet) }}">Voir</a>
                </div>
            @endforeach
        </div>
    </section>
@endsection
