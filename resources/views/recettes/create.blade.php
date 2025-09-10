<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/recettes-forms.css') }}">
    
    <div class="recette-container">
        <div class="recette-wrapper">
            <div class="recette-header">
                <h1 class="recette-title">Créer une nouvelle recette</h1>
                <p class="recette-subtitle">Partagez votre recette avec la communauté</p>
            </div>
            
            <div class="recette-form-container">
                <form action="{{ route('recettes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Titre de la recette *</label>
                        <input type="text" 
                               name="titre" 
                               value="{{ old('titre') }}"
                               placeholder="Ex: Spaghetti à la carbonara"
                               class="form-input"
                               required>
                        @error('titre')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description *</label>
                        <textarea name="description" 
                                  rows="3"
                                  placeholder="Décrivez votre recette en quelques mots..."
                                  class="form-textarea"
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-grid">
                        <div>
                            <label class="form-label">Type de plat</label>
                            <select name="type" class="form-select">
                                <option value="">-- Choisir --</option>
                                <option value="entree" {{ old('type') == 'entree' ? 'selected' : '' }}>Entrée</option>
                                <option value="plat" {{ old('type') == 'plat' ? 'selected' : '' }}>Plat principal</option>
                                <option value="dessert" {{ old('type') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                                <option value="apero" {{ old('type') == 'apero' ? 'selected' : '' }}>Apéritif</option>
                                <option value="boisson" {{ old('type') == 'boisson' ? 'selected' : '' }}>Boisson</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Temps (minutes)</label>
                            <input type="number" 
                                   name="temps_preparation" 
                                   value="{{ old('temps_preparation') }}"
                                   min="1"
                                   placeholder="30"
                                   class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Portions</label>
                            <input type="number" 
                                   name="portions" 
                                   value="{{ old('portions', 4) }}"
                                   min="1"
                                   placeholder="4"
                                   class="form-input">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tags alimentaires</label>
                        
                        <div class="tags-grid">
                            <label class="checkbox-label">
                                <input type="checkbox" name="tags[]" value="vegetarien" class="checkbox-input" {{ in_array('vegetarien', old('tags', [])) ? 'checked' : '' }}>
                                <span>Végétarien</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="tags[]" value="vegan" class="checkbox-input" {{ in_array('vegan', old('tags', [])) ? 'checked' : '' }}>
                                <span>Vegan</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="tags[]" value="sans-gluten" class="checkbox-input" {{ in_array('sans-gluten', old('tags', [])) ? 'checked' : '' }}>
                                <span>Sans gluten</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="tags[]" value="sans-lactose" class="checkbox-input" {{ in_array('sans-lactose', old('tags', [])) ? 'checked' : '' }}>
                                <span>Sans lactose</span>
                            </label>
                        </div>
                        
                        <div>
                            <label class="form-label-secondary">Ajouter des tags personnalisés (optionnel)</label>
                            <input type="text" 
                                   name="tags_custom"
                                   value="{{ old('tags_custom') }}"
                                   placeholder="Ex: rapide, facile, économique (séparez par des virgules)"
                                   class="form-input-small">
                            <p class="help-text">Tapez vos tags séparés par des virgules (ex: épicé, familial, festif)</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ingrédients *</label>
                        <div class="ingredients-input-container">
                            <input type="text" 
                                   id="ingredient-input"
                                   placeholder="Ex: 100g de tomate, 2 oignons..."
                                   class="ingredients-input">
                            <button type="button" 
                                    onclick="ajouterIngredient()"
                                    class="btn-add">
                                Ajouter
                            </button>
                        </div>
                        <div id="liste-ingredients" class="ingredients-empty">
                            Aucun ingrédient ajouté
                        </div>
                        <div id="ingredients-error" class="error-message" style="display: none;">
                            Vous devez ajouter au moins un ingrédient.
                        </div>
                        @error('ingredients')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <textarea id="ingredients" name="ingredients" style="display: none;">{{ old('ingredients') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Instructions *</label>
                        <textarea name="instructions" 
                                  rows="6"
                                  placeholder="1. Faire bouillir l'eau...&#10;2. Cuire les pâtes...&#10;3. Préparer la sauce..."
                                  class="form-textarea"
                                  required>{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Photo (optionnel)</label>
                        <input type="file" 
                               name="image" 
                               accept=".jpg,.jpeg,.png,.webp"
                               class="form-input">
                        <p class="help-text-large">Formats acceptés: JPG, PNG, WEBP (max 2MB)</p>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('recettes.index') }}" class="btn-secondary">Annuler</a>
                        <button type="submit" class="btn-primary">Créer la recette</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/ingredients.js') }}"></script>
</x-app-layout>
