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
        // Validation des données du formulaire
        $validated = $request->validate([
            'titre' => 'required|max:255',
            'description' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'type' => 'nullable|string',
            'temps_preparation' => 'nullable|integer|min:1',
            'portions' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);
        
        // Ajouter l'ID de l'utilisateur connecté
        $validated['user_id'] = auth()->id();
        
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
