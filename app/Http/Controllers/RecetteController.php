<?php

namespace App\Http\Controllers;

use App\Models\Recette;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecetteController extends Controller
{
    /**
     * Display a listing of the resource.
     * FORMATION: Cette méthode affiche la liste du CATALOGUE de recettes (tous utilisateurs)
     */
    public function index(Request $request)
    {
        // Commencer la requête avec les relations
        $query = Recette::with('user');
        
        // Appliquer les filtres
        $this->applyFilters($query, $request);
        
        // Récupérer les recettes filtrées
        $recettes = $query->latest()->get();
        
        // Retourner la vue avec les données et indiquer que c'est le CATALOGUE de recettes
        return view('recettes.index', compact('recettes'))->with('pageTitle', 'Catalogue de recettes');
    }

    /**
     * Display user's own recipes.
     * FORMATION: Cette méthode affiche seulement les recettes de l'utilisateur connecté
     */
    public function mesRecettes(Request $request)
    {
        // Commencer la requête avec seulement les recettes de l'utilisateur connecté
        $query = Recette::where('user_id', auth()->id())->with('user');
        
        // Appliquer les filtres
        $this->applyFilters($query, $request);
        
        // Récupérer les recettes filtrées
        $recettes = $query->latest()->get();
        
        // Utiliser la même vue mais avec seulement nos recettes
        return view('recettes.index', compact('recettes'))->with('pageTitle', 'Mes recettes');
    }

    /**
     * Show the form for creating a new resource.
     * FORMATION: Affiche le formulaire pour créer une nouvelle recette
     */
    public function create()
    {
        // Simplement afficher le formulaire de création
        return view('recettes.create');
    }

    /**
     * Store a newly created resource in storage.
     * FORMATION: Traite les données du formulaire et sauvegarde en base
     */
    public function store(Request $request)
    {
        // Messages d'erreur personnalisés
        $messages = [
            'titre.required' => 'Le titre de la recette est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'ingredients.required' => 'Vous devez ajouter au moins un ingrédient.',
            'instructions.required' => 'Les instructions de préparation sont obligatoires.',
            'image.mimes' => 'Le format du fichier est invalide. Seuls les formats JPG, PNG et WEBP sont acceptés.',
            'image.max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.'
        ];
        
        // Validation des données du formulaire
        $validated = $request->validate([
            'titre' => 'required|max:255',
            'description' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'type' => 'nullable|string',
            'temps_preparation' => 'nullable|integer|min:1',
            'portions' => 'nullable|integer|min:1',
            'image' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string|in:sans-gluten,vegetarien,vegan,sans-lactose',
            'tags_custom' => 'nullable|string|max:255'
        ], $messages);
        
        // Traitement des tags : combiner les tags cochés et les tags personnalisés
        $allTags = [];
        
        // Ajouter les tags cochés
        if (isset($validated['tags'])) {
            $allTags = array_merge($allTags, $validated['tags']);
        }
        
        // Ajouter les tags personnalisés
        if (!empty($validated['tags_custom'])) {
            $customTags = array_map('trim', explode(',', $validated['tags_custom']));
            $customTags = array_filter($customTags); // Enlever les éléments vides
            $customTags = array_map('strtolower', $customTags); // Mettre en minuscules
            $allTags = array_merge($allTags, $customTags);
        }
        
        // Enlever les doublons et assigner les tags finaux
        $validated['tags'] = array_unique($allTags);
        
        // Supprimer tags_custom car on n'en a plus besoin
        unset($validated['tags_custom']);
        
        // Ajouter l'ID de l'utilisateur connecté
        $validated['user_id'] = auth()->id();
        
        // Conserver tel quel ce que l'utilisateur a saisi pour les ingrédients
        // (les tirets sont déjà gérés côté JS)
        
        // Laisser les instructions telles que saisies (numérotation gérée côté client)

        // Gérer l'upload d'image si présente - convertir en base64
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageData = base64_encode(file_get_contents($image->getRealPath()));
            $validated['image'] = 'data:image/jpeg;base64,' . $imageData;
        }
        
        // Créer la recette en base de données
        Recette::create($validated);
        
        // Rediriger avec message de succès
        return redirect()->route('recettes.index')
                        ->with('success', 'Recette créée avec succès !');
    }

    /**
     * Display the specified resource.
     * FORMATION: Affiche les détails d'UNE recette spécifique
     */
    public function show(Recette $recette)
    {
        // Laravel fait automatiquement : Recette::findOrFail($id)
        // Si la recette n'existe pas, erreur 404 automatique !
        
        // Charger les infos du propriétaire pour optimiser
        $recette->load('user');
        
        // Retourner la vue avec la recette
        return view('recettes.show', compact('recette'));
    }

    /**
     * Show the form for editing the specified resource.
     * FORMATION: Affiche le formulaire pré-rempli pour modifier une recette
     */
    public function edit(Recette $recette)
    {
        // Vérifier que l'utilisateur connecté est le propriétaire
        if ($recette->user_id !== auth()->id()) {
            abort(403, 'Vous ne pouvez pas modifier cette recette.');
        }
        
        // Afficher le formulaire pré-rempli
        return view('recettes.edit', compact('recette'));
    }

    /**
     * Update the specified resource in storage.
     * FORMATION: Met à jour une recette existante
     */
    public function update(Request $request, Recette $recette)
    {
        // Vérifier que l'utilisateur connecté est le propriétaire
        if ($recette->user_id !== auth()->id()) {
            abort(403, 'Vous ne pouvez pas modifier cette recette.');
        }
        
        // Messages d'erreur personnalisés
        $messages = [
            'titre.required' => 'Le titre de la recette est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'ingredients.required' => 'Vous devez ajouter au moins un ingrédient.',
            'instructions.required' => 'Les instructions de préparation sont obligatoires.',
            'image.mimes' => 'Le format du fichier est invalide. Seuls les formats JPG, PNG et WEBP sont acceptés.',
            'image.max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.'
        ];
        
        // Validation (même que store mais pour modification)
        $validated = $request->validate([
            'titre' => 'required|max:255',
            'description' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'type' => 'nullable|string',
            'temps_preparation' => 'nullable|integer|min:1',
            'portions' => 'nullable|integer|min:1',
            'image' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string|in:sans-gluten,vegetarien,vegan,sans-lactose',
            'tags_custom' => 'nullable|string|max:255'
        ], $messages);
        
        // Traitement des tags : combiner les tags cochés et les tags personnalisés
        $allTags = [];
        
        // Ajouter les tags cochés
        if (isset($validated['tags'])) {
            $allTags = array_merge($allTags, $validated['tags']);
        }
        
        // Ajouter les tags personnalisés
        if (!empty($validated['tags_custom'])) {
            $customTags = array_map('trim', explode(',', $validated['tags_custom']));
            $customTags = array_filter($customTags); // Enlever les éléments vides
            $customTags = array_map('strtolower', $customTags); // Mettre en minuscules
            $allTags = array_merge($allTags, $customTags);
        }
        
        // Enlever les doublons et assigner les tags finaux
        $validated['tags'] = array_unique($allTags);
        
        // Supprimer tags_custom car on n'en a plus besoin
        unset($validated['tags_custom']);
        
        // Conserver tel quel ce que l'utilisateur a saisi pour les ingrédients
        // (les tirets sont déjà gérés côté JS)

        // Laisser les instructions telles que saisies (numérotation gérée côté client)
        
        // Gérer nouvelle image si uploadée
        if ($request->hasFile('image')) {
            // Convertir l'image en base64 et la stocker
            $image = $request->file('image');
            $imageData = base64_encode(file_get_contents($image->getRealPath()));
            $validated['image'] = 'data:image/jpeg;base64,' . $imageData;
        }
        
        // Mettre à jour la recette
        $recette->update($validated);
        
        // Rediriger vers la recette modifiée
        return redirect()->route('recettes.show', $recette)
                        ->with('success', 'Recette modifiée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     * FORMATION: Supprime définitivement une recette
     */
    public function destroy(Recette $recette)
    {
        // Vérifier que l'utilisateur connecté est le propriétaire
        if ($recette->user_id !== auth()->id()) {
            abort(403, 'Vous ne pouvez pas supprimer cette recette.');
        }
        
        // Supprimer la recette de la base de données
        // Plus besoin de supprimer l'image car elle est stockée en Base64 dans la DB
        $recette->delete();
        
        // Rediriger vers la liste avec message
        return redirect()->route('recettes.index')
                        ->with('success', 'Recette supprimée avec succès !');
    }

    /**
     * Appliquer les filtres de recherche et de filtrage
     */
    private function applyFilters($query, Request $request)
    {
        // Filtre par recherche dans le titre, description ou ingrédients
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('ingredients', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par type de plat
        if ($request->filled('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filtre par tags alimentaires (recherche dans la colonne JSON tags)
        // Logique AND : la recette doit avoir TOUS les tags sélectionnés
        if ($request->filled('tags')) {
            $tags = $request->tags;
            foreach ($tags as $tag) {
                $query->whereRaw("tags::jsonb @> ?::jsonb", [json_encode([$tag])]);
            }
        }

        // Filtre par tags personnalisés (recherche intelligente dans la colonne JSON tags)
        if ($request->filled('tags_custom')) {
            $tagsCustom = explode(',', $request->tags_custom);
            $query->where(function($q) use ($tagsCustom) {
                foreach ($tagsCustom as $tag) {
                    $tag = trim($tag); // Enlever les espaces en début/fin
                    if (!empty($tag)) {
                        $q->where(function($subQ) use ($tag) {
                            // Recherche exacte avec PostgreSQL
                            $subQ->orWhereRaw("tags::jsonb @> ?::jsonb", [json_encode([$tag])])
                                 // Recherche avec espaces remplacés par des tirets (pour "sans gl" trouver "sans-gluten")
                                 ->orWhereRaw("tags::jsonb @> ?::jsonb", [json_encode([str_replace(' ', '-', $tag)])])
                                 // Recherche avec tirets remplacés par des espaces (pour "sans-gluten" trouver "sans gl")
                                 ->orWhereRaw("tags::jsonb @> ?::jsonb", [json_encode([str_replace('-', ' ', $tag)])])
                                 // Recherche avec LIKE sur le JSON (plus flexible pour les accents)
                                 ->orWhereRaw("tags::text LIKE ?", ["%\"{$tag}\"%"])
                                 // Recherche partielle (pour "sans glu" trouver "sans-gluten")
                                 ->orWhereRaw("tags::text LIKE ?", ["%{$tag}%"])
                                 // Recherche avec espaces remplacés par des tirets (pour "sans gl" trouver "sans-gluten")
                                 ->orWhereRaw("tags::text LIKE ?", ["%" . str_replace(' ', '-', $tag) . "%"])
                                 // Recherche avec tirets remplacés par des espaces (pour "sans-gluten" trouver "sans gl")
                                 ->orWhereRaw("tags::text LIKE ?", ["%" . str_replace('-', ' ', $tag) . "%"])
                                 // Recherche avec caractères unicode échappés (pour "ét" trouver "été")
                                 ->orWhereRaw("tags::text LIKE ?", ["%" . str_replace(['é', 'è', 'ê', 'ë', 'à', 'á', 'â', 'ä', 'ù', 'ú', 'û', 'ü', 'ì', 'í', 'î', 'ï', 'ò', 'ó', 'ô', 'ö', 'ç'], ['\\u00e9', '\\u00e8', '\\u00ea', '\\u00eb', '\\u00e0', '\\u00e1', '\\u00e2', '\\u00e4', '\\u00f9', '\\u00fa', '\\u00fb', '\\u00fc', '\\u00ec', '\\u00ed', '\\u00ee', '\\u00ef', '\\u00f2', '\\u00f3', '\\u00f4', '\\u00f6', '\\u00e7'], $tag) . "%"])
                                 // Recherche avec normalisation des accents (inverser la logique)
                                 ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tags::text, 'é', 'e'), 'è', 'e'), 'ê', 'e'), 'ë', 'e') LIKE ?", ["%\"{$tag}\"%"])
                                 ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tags::text, 'à', 'a'), 'á', 'a'), 'â', 'a'), 'ä', 'a') LIKE ?", ["%\"{$tag}\"%"])
                                 ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tags::text, 'ù', 'u'), 'ú', 'u'), 'û', 'u'), 'ü', 'u') LIKE ?", ["%\"{$tag}\"%"])
                                 ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tags::text, 'ì', 'i'), 'í', 'i'), 'î', 'i'), 'ï', 'i') LIKE ?", ["%\"{$tag}\"%"])
                                 ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tags::text, 'ò', 'o'), 'ó', 'o'), 'ô', 'o'), 'ö', 'o') LIKE ?", ["%\"{$tag}\"%"])
                                 ->orWhereRaw("REPLACE(tags::text, 'ç', 'c') LIKE ?", ["%\"{$tag}\"%"])
                                 // Recherche partielle avec normalisation des accents
                                 ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tags::text, 'é', 'e'), 'è', 'e'), 'ê', 'e'), 'ë', 'e') LIKE ?", ["%{$tag}%"])
                                 ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tags::text, 'à', 'a'), 'á', 'a'), 'â', 'a'), 'ä', 'a') LIKE ?", ["%{$tag}%"])
                                 ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tags::text, 'ù', 'u'), 'ú', 'u'), 'û', 'u'), 'ü', 'u') LIKE ?", ["%{$tag}%"])
                                 ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tags::text, 'ì', 'i'), 'í', 'i'), 'î', 'i'), 'ï', 'i') LIKE ?", ["%{$tag}%"])
                                 ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tags::text, 'ò', 'o'), 'ó', 'o'), 'ô', 'o'), 'ö', 'o') LIKE ?", ["%{$tag}%"])
                                 ->orWhereRaw("REPLACE(tags::text, 'ç', 'c') LIKE ?", ["%{$tag}%"]);
                        });
                    }
                }
            });
        }

        // Filtre par temps de préparation
        if ($request->filled('temps_max')) {
            $query->where('temps_preparation', '<=', $request->temps_max);
        }

        // Filtre par nombre de portions
        if ($request->filled('portions')) {
            $query->where('portions', $request->portions);
        }
    }
}
