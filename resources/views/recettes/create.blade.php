<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ➕ {{ __('Créer une nouvelle recette') }}
            </h2>
            <a href="{{ route('recettes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Formulaire de création -->
                    <form action="{{ route('recettes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Titre -->
                        <div>
                            <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                                📝 Titre de la recette *
                            </label>
                            <input type="text" 
                                   id="titre" 
                                   name="titre" 
                                   value="{{ old('titre') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Ex: Spaghetti Bolognaise"
                                   required>
                            @error('titre')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                📖 Description *
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Décrivez votre recette en quelques mots..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type et temps/portions sur la même ligne -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                    🍽️ Type de plat
                                </label>
                                <select id="type" 
                                        name="type"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">-- Choisir --</option>
                                    <option value="entree" {{ old('type') == 'entree' ? 'selected' : '' }}>🥗 Entrée</option>
                                    <option value="plat" {{ old('type') == 'plat' ? 'selected' : '' }}>🍝 Plat principal</option>
                                    <option value="dessert" {{ old('type') == 'dessert' ? 'selected' : '' }}>🍰 Dessert</option>
                                    <option value="apero" {{ old('type') == 'apero' ? 'selected' : '' }}>🥂 Apéritif</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Temps de préparation -->
                            <div>
                                <label for="temps_preparation" class="block text-sm font-medium text-gray-700 mb-2">
                                    ⏱️ Temps (minutes)
                                </label>
                                <input type="number" 
                                       id="temps_preparation" 
                                       name="temps_preparation" 
                                       value="{{ old('temps_preparation') }}"
                                       min="1"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="30">
                                @error('temps_preparation')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Portions -->
                            <div>
                                <label for="portions" class="block text-sm font-medium text-gray-700 mb-2">
                                    👥 Portions
                                </label>
                                <input type="number" 
                                       id="portions" 
                                       name="portions" 
                                       value="{{ old('portions', 4) }}"
                                       min="1"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="4">
                                @error('portions')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Ingrédients -->
                        <div>
                            <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-2">
                                🛒 Ingrédients *
                            </label>
                            <textarea id="ingredients" 
                                      name="ingredients" 
                                      rows="6"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Listez vos ingrédients, un par ligne:&#10;- 500g de pâtes&#10;- 400g de viande hachée&#10;- 1 oignon&#10;- 2 tomates..."
                                      required>{{ old('ingredients') }}</textarea>
                            @error('ingredients')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instructions -->
                        <div>
                            <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                📋 Instructions de préparation *
                            </label>
                            <textarea id="instructions" 
                                      name="instructions" 
                                      rows="8"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Décrivez étape par étape comment préparer votre recette:&#10;1. Faire bouillir l'eau...&#10;2. Cuire les pâtes...&#10;3. Préparer la sauce..."
                                      required>{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                📷 Photo de la recette (optionnel)
                            </label>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-gray-500 text-sm mt-1">Formats acceptés: JPG, PNG, GIF (max 2MB)</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="flex justify-between items-center pt-6 border-t">
                            <a href="{{ route('recettes.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                ❌ Annuler
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                💾 Créer la recette
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
