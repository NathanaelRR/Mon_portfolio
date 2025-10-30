<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projet;
use Illuminate\Support\Facades\Storage;

class ProjetController extends Controller
{
    public function index()
    {
        $projets = Projet::all();

        $technoList = $projets
            ->pluck('technologies')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('index', compact('projets', 'technoList'));
    }

    public function create()
    {
        return view('projets.bo_nthnl_76.create');
    }

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

        $projet = Projet::create([
            'nom' => $data['nom'],
            'type' => $data['type'],
            'resume' => $data['resume'] ?? null,
            'apport_personnel' => $data['apport_personnel'] ?? null,
            'description_difficultes' => $data['description_difficultes'] ?? null,
            'technologies' => $data['technologies'] ?? [],
        ]);

        $this->storeEtapes($projet, $data['etapes'] ?? []);
        $this->storeImages($projet, $request);

        return redirect()->route('index')->with('success', 'Projet créé !');
    }

    public function show(Projet $projet)
    {
        return view('projets.show', compact('projet'));
    }

    public function edit(Projet $projet)
    {
        return view('projets.bo_nthnl_76.edit', compact('projet'));
    }

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

        $projet->update([
            'nom' => $data['nom'],
            'type' => $data['type'],
            'resume' => $data['resume'] ?? null,
            'apport_personnel' => $data['apport_personnel'] ?? null,
            'description_difficultes' => $data['description_difficultes'] ?? null,
            'technologies' => $data['technologies'] ?? [],
        ]);

        $this->deleteEtapes($projet, $request);
        $this->updateEtapes($projet, $data['etapes'] ?? []);
        $this->deleteImages($projet, $request);
        $this->storeImages($projet, $request);

        return redirect()->route('projets.show', $projet)->with('success', 'Projet mis à jour !');
    }

    private function storeEtapes(Projet $projet, array $etapes)
    {
        foreach (['conception', 'developpement'] as $categorie) {
            if (!empty($etapes[$categorie])) {
                foreach ($etapes[$categorie] as $index => $etape) {
                    $projet->etapes()->create([
                        'categorie' => $categorie,
                        'titre' => $etape['titre'],
                        'description' => $etape['description'] ?? null,
                        'ordre' => $index,
                    ]);
                }
            }
        }
    }

    private function storeImages(Projet $projet, Request $request)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                // Vérifie que le fichier est valide
                if ($file->isValid()) {
                    // Génère un nom unique pour éviter les conflits
                    $filename = time() . '_' . $file->getClientOriginalName();

                    // Upload sur S3 dans le dossier "projets"
                    $path = $file->storeAs('projets', $filename, 's3');

                    // Vérifie que l'upload a réussi
                    if (!$path) {
                        // Tu peux logger l'erreur ou gérer comme tu veux
                        \Log::error("Échec de l'upload pour le fichier : " . $file->getClientOriginalName());
                        continue;
                    }

                    // Enregistre dans la BDD
                    $projet->images()->create([
                        'path' => $path,
                        'ordre' => $index,
                    ]);
                } else {
                    \Log::warning("Fichier invalide : " . $file->getClientOriginalName());
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
                    Storage::disk('s3')->delete($image->path);
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
                            'ordre' => $etape['ordre'] ?? 0, 
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

    public function destroy(Projet $projet)
    {
        foreach ($projet->images as $image) {
            Storage::disk('s3')->delete($image->path);
        }

        $projet->etapes()->delete();
        $projet->images()->delete();
        $projet->delete();

        return redirect()->route('index')->with('success', 'Projet supprimé avec succès !');
    }
}
