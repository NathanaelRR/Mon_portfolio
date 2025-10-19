<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projet;
use App\Models\Etape;
use App\Models\ProjetImage;
use Illuminate\Support\Facades\Storage;

class ProjetController extends Controller
{

    public function index()
    {
        $projets = Projet::all();

        // Extraire les technologies uniques
        $technoList = $projets
            ->pluck('technologies')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('index', compact('projets', 'technoList'));
    }

    // -----------------------------
    // Formulaire de création
    // -----------------------------
    public function create()
    {
        return view('projets.bo_nthnl_76.create');
    }

    // -----------------------------
    // Stocke un nouveau projet
    // -----------------------------
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string',
            'resume' => 'nullable|string',
            'apport_personnel' => 'nullable|string',
            'description_difficultes' => 'nullable|string',
            'technologies' => 'nullable|array',
            'etapes' => 'nullable|array',
            'etapes.conception.*.titre' => 'required|string|max:255',
            'etapes.conception.*.description' => 'nullable|string',
            'etapes.developpement.*.titre' => 'required|string|max:255',
            'etapes.developpement.*.description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        // Créer le projet
        $projet = Projet::create([
            'nom' => $data['nom'],
            'type' => $data['type'],
            'resume' => $data['resume'] ?? null,
            'apport_personnel' => $data['apport_personnel'] ?? null,
            'description_difficultes' => $data['description_difficultes'] ?? null,
            'technologies' => $data['technologies'] ?? [],
        ]);

        // Ajouter les étapes
        $this->storeEtapes($projet, $data['etapes'] ?? []);

        // Ajouter les images
        $this->storeImages($projet, $request);

        return redirect()->route('index')->with('success', 'Projet créé !');
    }

    // -----------------------------
    // Afficher un projet
    // -----------------------------
    public function show(Projet $projet)
    {
        return view('projets.show', compact('projet'));
    }

    // -----------------------------
    // Formulaire d'édition
    // -----------------------------
    public function edit(Projet $projet)
    {
        return view('projets.bo_nthnl_76.edit', compact('projet'));
    }

    // -----------------------------
    // Mettre à jour un projet
    // -----------------------------
    public function update(Request $request, Projet $projet)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string',
            'resume' => 'nullable|string',
            'apport_personnel' => 'nullable|string',
            'description_difficultes' => 'nullable|string',
            'technologies' => 'nullable|array',
            'etapes' => 'nullable|array',
            'etapes.conception.*.titre' => 'required|string|max:255',
            'etapes.conception.*.description' => 'nullable|string',
            'etapes.developpement.*.titre' => 'required|string|max:255',
            'etapes.developpement.*.description' => 'nullable|string',
            'delete_etapes_conception' => 'nullable|array',
            'delete_etapes_developpement' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'delete_images' => 'nullable|array',
        ]);

        // Mettre à jour le projet
        $projet->update([
            'nom' => $data['nom'],
            'type' => $data['type'],
            'resume' => $data['resume'] ?? null,
            'apport_personnel' => $data['apport_personnel'] ?? null,
            'description_difficultes' => $data['description_difficultes'] ?? null,
            'technologies' => $data['technologies'] ?? [],
        ]);

        // Supprimer les étapes sélectionnées
        $this->deleteEtapes($projet, $request);

        // Mettre à jour ou ajouter les étapes
        $this->updateEtapes($projet, $data['etapes'] ?? []);

        // Supprimer les images sélectionnées
        $this->deleteImages($projet, $request);

        // Ajouter les nouvelles images
        $this->storeImages($projet, $request);

        return redirect()->route('projets.show', $projet)->with('success', 'Projet mis à jour !');
    }

    // -----------------------------
    // Fonctions auxiliaires
    // -----------------------------
    private function storeEtapes(Projet $projet, array $etapes)
    {
        if (!empty($etapes['conception'])) {
            foreach ($etapes['conception'] as $index => $etape) {
                $projet->etapes()->create([
                    'categorie' => 'conception',
                    'titre' => $etape['titre'],
                    'description' => $etape['description'] ?? null,
                    'ordre' => $index,
                ]);
            }
        }

        if (!empty($etapes['developpement'])) {
            foreach ($etapes['developpement'] as $index => $etape) {
                $projet->etapes()->create([
                    'categorie' => 'developpement',
                    'titre' => $etape['titre'],
                    'description' => $etape['description'] ?? null,
                    'ordre' => $index,
                ]);
            }
        }
    }

    // private function storeImages(Projet $projet, Request $request)
    // {
    //     if ($request->hasFile('images')) {
    //         foreach ($request->file('images') as $index => $file) {
    //             $path = $file->store('projets', 'public');
    //             $projet->images()->create([
    //                 'path' => $path,
    //                 'ordre' => $index,
    //             ]);
    //         }
    //     }
    // }

    private function storeImages(Projet $projet, Request $request)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . $file->getClientOriginalName();

                // Crée le dossier public/projets si inexistant
                $destination = public_path('projets');
                if (!file_exists($destination)) {
                    mkdir($destination, 0777, true);
                }

                // Déplace le fichier
                $file->move($destination, $filename);

                // Vérifie que le fichier a bien été déplacé
                if (file_exists($destination . '/' . $filename)) {
                    $projet->images()->create([
                        'path' => 'projets/' . $filename,
                        'ordre' => $index,
                    ]);
                }
            }
        }
    }

     private function deleteImages(Projet $projet, Request $request)
    {
        if (!empty($request->delete_images)) {
            foreach ($request->delete_images as $imageId) {
                $image = $projet->images()->find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }
        }
    }

    private function deleteEtapes(Projet $projet, Request $request)
    {
        if (!empty($request->delete_etapes_conception)) {
            $projet->etapes()->whereIn('id', $request->delete_etapes_conception)->delete();
        }
        if (!empty($request->delete_etapes_developpement)) {
            $projet->etapes()->whereIn('id', $request->delete_etapes_developpement)->delete();
        }
    }

    private function updateEtapes(Projet $projet, array $etapes)
    {
        foreach (['conception', 'developpement'] as $categorie) {
            if (!empty($etapes[$categorie])) {
                foreach ($etapes[$categorie] as $key => $etape) {
                    if (str_starts_with($key, 'new_')) {
                        $projet->etapes()->create([
                            'categorie' => $categorie,
                            'titre' => $etape['titre'],
                            'description' => $etape['description'] ?? null,
                        ]);
                    } else {
                        $existing = $projet->etapes()->find($key);
                        if ($existing) {
                            $existing->update([
                                'titre' => $etape['titre'],
                                'description' => $etape['description'] ?? null,
                            ]);
                        }
                    }
                }
            }
        }
    }

    // -----------------------------
    // Supprimer un projet
    // -----------------------------
    public function destroy(Projet $projet)
    {
        // Supprimer les images physiques
        foreach ($projet->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        // Supprimer toutes les étapes et images en base
        $projet->etapes()->delete();
        $projet->images()->delete();

        // Supprimer le projet
        $projet->delete();

        return redirect()->route('index')->with('success', 'Projet supprimé avec succès !');
    }
}
