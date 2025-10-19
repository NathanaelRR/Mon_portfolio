@extends('layouts.app')

@section('title', 'Modifier : ' . $projet->nom)

@section('content')

    <script src="{{ asset('js/etape_edit.js') }}"></script>
    <script src="{{ asset('js/techno.js') }}"></script>

    <div class="projet-detail">
        <a href="{{ route('index') }}" class="btn-secondary">← Retour</a>

    <form action="{{ route('projets.update', $projet) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

            <div class="projet-columns">

                <div class="bloc-left">
                    <label>Nom</label>
                        <input type="text" name="nom" value="{{ old('nom', $projet->nom) }}">

                    <label>Type</label>
                        <select name="type" required>
                            <option value="Professionnel" {{ old('type', $projet->type ?? '') == 'Professionnel' ? 'selected' : '' }}>Professionnel</option>
                            <option value="Académique" {{ old('type', $projet->type ?? '') == 'Académique' ? 'selected' : '' }}>Académique</option>
                            <option value="Personnel" {{ old('type', $projet->type ?? '') == 'Personnel' ? 'selected' : '' }}>Personnel</option>
                        </select>

                        @php
                            $technoList = ['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'HTML', 'CSS', 'Python', 'MySQL', 'Docker'];

                            $selected = is_array($projet->technologies) // Technologies déjà sélectionnées pour ce projet
                                ? $projet->technologies
                                : json_decode($projet->technologies ?? '[]', true);
                        @endphp

                        <label>Technologies utilisées</label>
                            <div id="techno-buttons">
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
                                    <input type="hidden" name="technologies[]" value="{{ $techno }}">
                                @endforeach
                            </div>

                        <label>Résumé</label>
                        <textarea name="resume">{{ old('resume', $projet->resume) }}</textarea>

                        <label>Images</label>
                        @if($projet->images->count())
                            <div class="image-section-container">
                                <div class="image-section" id="imageCarousel">
                                    @foreach($projet->images as $image)
                                        <figure class="image-item">
                                            {{-- <img src="{{ asset('storage/' . $image->path) }}" alt="Image du projet"> --}}
                                            <img src="{{ Storage::disk('s3')->url($image->path) }}" alt="Image du projet">
                                            <!-- Checkbox pour suppression -->
                                            <label class="delete-image-label">
                                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                                                Supprimer
                                            </label>
                                        </figure>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <input type="file" name="images[]" multiple>
                </div>

                <div class="bloc-right">

                    <label>Étapes de conception</label>
                        <div id="etapes-conception">
                            @foreach($projet->etapesConception as $etape)
                                <div class="etape" data-id="{{ $etape->id }}" data-categorie="conception">
                                    <input type="text"
                                        name="etapes[conception][{{ $etape->id }}][titre]"
                                        value="{{ $etape->titre }}"
                                        placeholder="Titre">
                                    <textarea name="etapes[conception][{{ $etape->id }}][description]"
                                            placeholder="Description">{{ $etape->description }}</textarea>
                                    <button type="button" class="remove-etape">Supprimer</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-etape">+ Ajouter une étape</button>

                        <label>Étapes de développement</label>
                        <div id="etapes-developpement">
                            @foreach($projet->etapesDeveloppement as $etape)
                                <div class="etape" data-id="{{ $etape->id }}" data-categorie="developpement">
                                    <input type="text"
                                        name="etapes[developpement][{{ $etape->id }}][titre]"
                                        value="{{ $etape->titre }}"
                                        placeholder="Titre">
                                    <textarea name="etapes[developpement][{{ $etape->id }}][description]"
                                            placeholder="Description">{{ $etape->description }}</textarea>
                                    <button type="button" class="remove-etape">Supprimer</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-etape-dev">+ Ajouter une étape</button>


                    <label>Difficultés</label>
                        <textarea name="description_difficultes">{{ old('description_difficultes', $projet->description_difficultes) }}</textarea>

                    <label>Apport personnel</label>
                        <textarea name="apport_personnel">{{ old('apport_personnel', $projet->apport_personnel) }}</textarea>
                        <button type="submit" class="btn-update">Mettre à jour le projet</button>

                </div>
            </div>
        </form>

    </div>

    <form action="{{ route('projets.destroy', $projet) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce projet ?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Supprimer le projet</button>
    </form>

@endsection

