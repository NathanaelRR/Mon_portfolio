@extends('layouts.app')

@section('title', 'Ajouter un projet')

@section('content')
    {{-- scripts qui gèrent le dynamisme de l'affichage --}}
    <script src="{{ asset('js/etapes.js') }}"></script>
    <script src="{{ asset('js/techno.js') }}"></script>

    <div class="projet-detail">
        <a href="{{ route('index') }}" class="btn-secondary">← Retour</a>

        <form action="{{ route('projets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="projet-columns">

                {{-- BLOC GAUCHE  --}}
                <div class="bloc-left">
                    <label>Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required>

                    <label>Type</label>
                        <select name="type" required>
                            <option value="Professionnel" {{ old('type', $projet->type ?? '') == 'Professionnel' ? 'selected' : '' }}>Professionnel</option>
                            <option value="Académique" {{ old('type', $projet->type ?? '') == 'Académique' ? 'selected' : '' }}>Académique</option>
                            <option value="Personnel" {{ old('type', $projet->type ?? '') == 'Personnel' ? 'selected' : '' }}>Personnel</option>
                        </select>

                    <label>Technologies utilisées</label>
                        <div id="techno-buttons">
                            @php
                            $technoList = [
                                // Langages
                                'PHP', 'JavaScript', 'Python', 'Java', 'C#', 'Ruby', 'TypeScript', 'Swift', 'Kotlin', 'Pascal', 'R',

                                // Front-end
                                'HTML', 'CSS', 'Bootstrap', 'Tailwind CSS', 'Vue.js', 'React', 'Angular', 'jQuery',

                                // Back-end / Frameworks
                                'Laravel', 'Symfony', 'Django', 'Flask', 'Express.js', 'Spring', 'Ruby on Rails', 'ASP.NET',

                                // Bases de données
                                'MySQL', 'PostgreSQL', 'MongoDB', 'SQLite', 'MariaDB', 'Access',

                                // Mobile
                                'React Native', 'Flutter', 'Swift', 'Kotlin'
                            ];
                                $selected = old('technologies', $projet->technologies ?? []);
                            @endphp

                            @foreach($technoList as $techno)
                                <button type="button"
                                        class="techno-btn @if(in_array($techno, $selected)) selected @endif"
                                        data-techno="{{ $techno }}">
                                    {{ $techno }}
                                </button>
                            @endforeach
                        </div>
                        <div id="techno-container">
                            @foreach($selected as $techno)
                                <span class="techno-item" data-techno="{{ $techno }}">
                                    {{ $techno }}
                                    <button type="button" class="remove-techno">×</button>
                                </span>
                                <input type="hidden" name="technologies[]" value="{{ $techno }}">
                            @endforeach
                        </div>

                    <label>Résumé</label>
                        <textarea name="resume">{{ old('resume') }}</textarea>

                    <label>Images</label>
                    {{-- Images --}}
                    <input type="file" name="images[]" multiple accept="image/*">
                </div>

                {{-- BLOC DROIT --}}
                <div class="bloc-right">

                    <label>Étapes de conception</label>
                        <div id="etapes-conception"> {{-- Géré par etapes.js --}}
                        </div>
                        <button type="button" id="add-etape" class="btn">+ Ajouter une étape</button>

                    <label>Étapes de développement</label>
                        <div id="etapes-developpement">
                        </div>
                        <button type="button" id="add-etape-dev" class="btn">+ Ajouter une étape</button>

                    <label>Difficultés</label>
                        <textarea name="description_difficultes">{{ old('description_difficultes') }}</textarea>

                    <label>Apport personnel</label>
                        <textarea name="apport_personnel">{{ old('apport_personnel') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn-primary">Créer le projet</button>
        </form>
    </div>
@endsection
