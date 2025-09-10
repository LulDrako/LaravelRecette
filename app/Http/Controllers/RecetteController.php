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
     * FORMATION: Cette méthode affiche la liste de TOUTES les recettes
     */
    public function index()
    {
        // Récupérer toutes les recettes avec leurs propriétaires
        // with('user') = optimisation pour éviter N+1 queries
        $recettes = Recette::with('user')->latest()->get();
        
        // Retourner la vue avec les données
        // compact('recettes') = ['recettes' => $recettes]
        return view('recettes.index', compact('recettes'));
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
        
        // Normaliser le format des ingrédients (convertir "- quantité nom" en "- quantité de nom")
        if (isset($validated['ingredients'])) {
            $lignes = explode("\n", $validated['ingredients']);
            $ingredientsNormalises = [];
            
            foreach ($lignes as $ligne) {
                $ligne = trim($ligne);
                if (!empty($ligne) && strpos($ligne, '-') === 0) {
                    // Enlever le tiret et les espaces
                    $contenu = trim(substr($ligne, 1));
                    
                    // Si ce n'est pas déjà au format "quantité de nom"
                    if (!preg_match('/\s+de\s+/', $contenu)) {
                        // Séparer en mots
                        $mots = explode(' ', $contenu);
                        if (count($mots) >= 2) {
                            $quantite = $mots[0];
                            $nom = implode(' ', array_slice($mots, 1));
                            
                            // Si le 2e mot ressemble à une unité, l'inclure dans la quantité
                            if (count($mots) > 2 && preg_match('/^(g|kg|ml|l|cl|cuillères?|c\.|cs|cc|tasses?|pièces?|tranches?)$/i', $mots[1])) {
                                $quantite = $mots[0] . ' ' . $mots[1];
                                $nom = implode(' ', array_slice($mots, 2));
                            }
                            
                            $ingredientsNormalises[] = "- $quantite de $nom";
                        } else {
                            $ingredientsNormalises[] = $ligne;
                        }
                    } else {
                        $ingredientsNormalises[] = $ligne;
                    }
                } else if (!empty($ligne)) {
                    $ingredientsNormalises[] = $ligne;
                }
            }
            
            $validated['ingredients'] = implode("\n", $ingredientsNormalises);
        }
        
        // Gérer l'upload d'image si présente
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('recettes', 'public');
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
        
        // Validation (même que store mais pour modification)
        $validated = $request->validate([
            'titre' => 'required|max:255',
            'description' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'type' => 'nullable|string',
            'temps_preparation' => 'nullable|integer|min:1',
            'portions' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Normaliser le format des ingrédients (convertir "- quantité nom" en "- quantité de nom")
        if (isset($validated['ingredients'])) {
            $lignes = explode("\n", $validated['ingredients']);
            $ingredientsNormalises = [];
            
            foreach ($lignes as $ligne) {
                $ligne = trim($ligne);
                if (!empty($ligne) && strpos($ligne, '-') === 0) {
                    // Enlever le tiret et les espaces
                    $contenu = trim(substr($ligne, 1));
                    
                    // Si ce n'est pas déjà au format "quantité de nom"
                    if (!preg_match('/\s+de\s+/', $contenu)) {
                        // Séparer en mots
                        $mots = explode(' ', $contenu);
                        if (count($mots) >= 2) {
                            $quantite = $mots[0];
                            $nom = implode(' ', array_slice($mots, 1));
                            
                            // Si le 2e mot ressemble à une unité, l'inclure dans la quantité
                            if (count($mots) > 2 && preg_match('/^(g|kg|ml|l|cl|cuillères?|c\.|cs|cc|tasses?|pièces?|tranches?)$/i', $mots[1])) {
                                $quantite = $mots[0] . ' ' . $mots[1];
                                $nom = implode(' ', array_slice($mots, 2));
                            }
                            
                            $ingredientsNormalises[] = "- $quantite de $nom";
                        } else {
                            $ingredientsNormalises[] = $ligne;
                        }
                    } else {
                        $ingredientsNormalises[] = $ligne;
                    }
                } else if (!empty($ligne)) {
                    $ingredientsNormalises[] = $ligne;
                }
            }
            
            $validated['ingredients'] = implode("\n", $ingredientsNormalises);
        }
        
        // Gérer nouvelle image si uploadée
        if ($request->hasFile('image')) {
            // Supprimer ancienne image si elle existe
            if ($recette->image) {
                Storage::disk('public')->delete($recette->image);
            }
            $validated['image'] = $request->file('image')->store('recettes', 'public');
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
        
        // Supprimer l'image si elle existe
        if ($recette->image) {
            Storage::disk('public')->delete($recette->image);
        }
        
        // Supprimer la recette de la base de données
        $recette->delete();
        
        // Rediriger vers la liste avec message
        return redirect()->route('recettes.index')
                        ->with('success', 'Recette supprimée avec succès !');
    }
}
