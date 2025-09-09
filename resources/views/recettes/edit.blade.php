@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier la recette : {{ $recette->titre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Messages d'erreur -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong>Erreurs :</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('recettes.update', $recette) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Titre -->
                        <div class="mb-6">
                            <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre de la recette *
                            </label>
                            <input type="text" 
                                   id="titre" 
                                   name="titre" 
                                   value="{{ old('titre', $recette->titre) }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description *
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      required>{{ old('description', $recette->description) }}</textarea>
                        </div>

                        <!-- Ingrédients -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                🛒 Ingrédients *
                            </label>
                            
                            <!-- Interface d'ajout d'ingrédients -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <div class="flex flex-wrap gap-3 mb-3">
                                    <input type="text" 
                                           id="ingredient-nom" 
                                           placeholder="Ex: Pâtes, Tomates, Oignon..."
                                           class="flex-1 min-w-48 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <input type="text" 
                                           id="ingredient-quantite" 
                                           placeholder="Ex: 500g, 2 pièces, 1 cuillère..."
                                           class="flex-1 min-w-32 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button type="button" 
                                            onclick="ajouterIngredient()"
                                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium">
                                        ➕ Ajouter
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600">💡 Tapez le nom de l'ingrédient et sa quantité, puis cliquez sur "Ajouter"</p>
                            </div>

                            <!-- Liste des ingrédients ajoutés -->
                            <div id="liste-ingredients" class="space-y-2 mb-4">
                                <!-- Les ingrédients ajoutés apparaîtront ici -->
                            </div>

                            <!-- Champ caché pour envoyer les données -->
                            <textarea id="ingredients" 
                                      name="ingredients" 
                                      class="hidden"
                                      required>{{ old('ingredients', $recette->ingredients) }}</textarea>
                            
                            <!-- Message si aucun ingrédient -->
                            <div id="message-vide" class="text-gray-500 text-center py-4 border-2 border-dashed border-gray-300 rounded-lg">
                                🥄 Aucun ingrédient ajouté. Commencez par en ajouter un ci-dessus !
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="mb-6">
                            <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                Instructions de préparation *
                            </label>
                            <textarea id="instructions" 
                                      name="instructions" 
                                      rows="8"
                                      placeholder="Décrivez les étapes de préparation..."
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      required>{{ old('instructions', $recette->instructions) }}</textarea>
                        </div>

                        <!-- Image actuelle -->
                        @if($recette->image)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Image actuelle
                                </label>
                                <img src="{{ Storage::url($recette->image) }}" 
                                     alt="{{ $recette->titre }}" 
                                     class="w-32 h-32 object-cover rounded-lg">
                            </div>
                        @endif

                        <!-- Nouvelle image -->
                        <div class="mb-6">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $recette->image ? 'Changer l\'image' : 'Ajouter une image' }} (optionnel)
                            </label>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-sm text-gray-500 mt-1">Formats acceptés : JPG, PNG, GIF. Taille max : 2MB</p>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Type de plat
                                </label>
                                <select id="type" 
                                        name="type"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Sélectionner...</option>
                                    <option value="Entrée" {{ old('type', $recette->type) === 'Entrée' ? 'selected' : '' }}>Entrée</option>
                                    <option value="Plat principal" {{ old('type', $recette->type) === 'Plat principal' ? 'selected' : '' }}>Plat principal</option>
                                    <option value="Dessert" {{ old('type', $recette->type) === 'Dessert' ? 'selected' : '' }}>Dessert</option>
                                    <option value="Boisson" {{ old('type', $recette->type) === 'Boisson' ? 'selected' : '' }}>Boisson</option>
                                    <option value="Apéritif" {{ old('type', $recette->type) === 'Apéritif' ? 'selected' : '' }}>Apéritif</option>
                                </select>
                            </div>

                            <!-- Temps de préparation -->
                            <div>
                                <label for="temps_preparation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Temps (minutes)
                                </label>
                                <input type="number" 
                                       id="temps_preparation" 
                                       name="temps_preparation" 
                                       value="{{ old('temps_preparation', $recette->temps_preparation) }}"
                                       min="1"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Nombre de portions -->
                            <div>
                                <label for="portions" class="block text-sm font-medium text-gray-700 mb-2">
                                    Portions
                                </label>
                                <input type="number" 
                                       id="portions" 
                                       name="portions" 
                                       value="{{ old('portions', $recette->portions) }}"
                                       min="1"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex flex-wrap gap-3">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                                ✅ Sauvegarder les modifications
                            </button>
                            <a href="{{ route('recettes.show', $recette) }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                                ❌ Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript pour la gestion des ingrédients -->
    <script>
        let ingredients = [];
        
        // Fonction pour ajouter un ingrédient
        function ajouterIngredient() {
            const nom = document.getElementById('ingredient-nom').value.trim();
            const quantite = document.getElementById('ingredient-quantite').value.trim();
            
            if (nom === '' || quantite === '') {
                alert('⚠️ Veuillez remplir le nom ET la quantité de l\'ingrédient !');
                return;
            }
            
            // Ajouter à la liste
            ingredients.push({ nom: nom, quantite: quantite });
            
            // Vider les champs
            document.getElementById('ingredient-nom').value = '';
            document.getElementById('ingredient-quantite').value = '';
            
            // Mettre à jour l'affichage
            afficherIngredients();
            mettreAJourChampCache();
        }
        
        // Fonction pour supprimer un ingrédient
        function supprimerIngredient(index) {
            ingredients.splice(index, 1);
            afficherIngredients();
            mettreAJourChampCache();
        }
        
        // Fonction pour afficher la liste des ingrédients
        function afficherIngredients() {
            const liste = document.getElementById('liste-ingredients');
            const messageVide = document.getElementById('message-vide');
            
            if (ingredients.length === 0) {
                liste.innerHTML = '';
                messageVide.style.display = 'block';
            } else {
                messageVide.style.display = 'none';
                liste.innerHTML = ingredients.map((ingredient, index) => `
                    <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">🥄</span>
                            <div>
                                <span class="font-medium text-gray-800">${ingredient.quantite}</span>
                                <span class="text-gray-600">${ingredient.nom}</span>
                            </div>
                        </div>
                        <button type="button" 
                                onclick="supprimerIngredient(${index})"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-medium">
                            🗑️ Supprimer
                        </button>
                    </div>
                `).join('');
            }
        }
        
        // Fonction pour mettre à jour le champ caché
        function mettreAJourChampCache() {
            const champCache = document.getElementById('ingredients');
            champCache.value = ingredients.map(ingredient => `- ${ingredient.quantite} ${ingredient.nom}`).join('\n');
        }
        
        // Permettre l'ajout avec la touche Entrée
        document.getElementById('ingredient-nom').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                ajouterIngredient();
            }
        });
        
        document.getElementById('ingredient-quantite').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                ajouterIngredient();
            }
        });
        
        // Charger les ingrédients existants de la recette
        document.addEventListener('DOMContentLoaded', function() {
            const ingredientsExistants = `{!! addslashes(old('ingredients', $recette->ingredients)) !!}`;
            console.log('Ingrédients bruts:', ingredientsExistants);
            
            if (ingredientsExistants) {
                const lignes = ingredientsExistants.split('\n').filter(line => line.trim() !== '');
                console.log('Lignes à parser:', lignes);
                
                lignes.forEach(ligne => {
                    ligne = ligne.trim();
                    if (ligne.startsWith('-')) {
                        // Enlever le tiret et les espaces
                        let contenu = ligne.substring(1).trim();
                        
                        // Essayer format "quantité de nom"
                        let match = contenu.match(/^(.+?)\s+de\s+(.+)$/);
                        if (match) {
                            ingredients.push({ nom: match[2], quantite: match[1] });
                            console.log('Format "de" détecté:', match[1], match[2]);
                        } else {
                            // Essayer format "quantité nom" (prendre les premiers mots comme quantité)
                            let mots = contenu.split(' ');
                            if (mots.length >= 2) {
                                // Supposer que les 1-2 premiers mots sont la quantité
                                let quantite = mots[0];
                                let nom = mots.slice(1).join(' ');
                                
                                // Si le 2e mot ressemble à une unité, l'inclure dans la quantité
                                if (mots.length > 2 && /^(g|kg|ml|l|cl|cuillères?|c\.|cs|cc|tasses?|pièces?|tranches?)$/i.test(mots[1])) {
                                    quantite = mots[0] + ' ' + mots[1];
                                    nom = mots.slice(2).join(' ');
                                }
                                
                                ingredients.push({ nom: nom, quantite: quantite });
                                console.log('Format simple détecté:', quantite, nom);
                            }
                        }
                    }
                });
                
                console.log('Ingrédients finaux:', ingredients);
                afficherIngredients();
            } else {
                afficherIngredients();
            }
        });
    </script>
</x-app-layout>
