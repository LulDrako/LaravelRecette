@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    
    <div class="recette-container">
        <div class="recette-wrapper">
            <div class="recette-header">
                <h1 class="recette-title">Modifier la recette</h1>
                <p class="recette-subtitle">{{ $recette->titre }}</p>
            </div>


            <div class="recette-form-container">
                <form action="{{ route('recettes.update', $recette) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    @if ($errors->any())
                        <div class="error-container">
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">Titre de la recette *</label>
                        <input type="text" 
                               name="titre" 
                               value="{{ old('titre', $recette->titre) }}"
                               placeholder="Ex: Spaghetti à la carbonara"
                               class="form-input"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description *</label>
                        <textarea name="description" 
                                  rows="3"
                                  placeholder="Décrivez votre recette en quelques mots..."
                                  class="form-textarea"
                                  required>{{ old('description', $recette->description) }}</textarea>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label class="form-label">Type de plat</label>
                            <select name="type" class="form-select">
                                <option value="">-- Choisir --</option>
                                <option value="entree" {{ old('type', $recette->type) == 'entree' ? 'selected' : '' }}>Entrée</option>
                                <option value="plat" {{ old('type', $recette->type) == 'plat' ? 'selected' : '' }}>Plat principal</option>
                                <option value="dessert" {{ old('type', $recette->type) == 'dessert' ? 'selected' : '' }}>Dessert</option>
                                <option value="apero" {{ old('type', $recette->type) == 'apero' ? 'selected' : '' }}>Apéritif</option>
                                <option value="boisson" {{ old('type', $recette->type) == 'boisson' ? 'selected' : '' }}>Boisson</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Temps (minutes)</label>
                            <input type="number" 
                                   name="temps_preparation" 
                                   value="{{ old('temps_preparation', $recette->temps_preparation) }}"
                                   min="1"
                                   placeholder="30"
                                   class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Portions</label>
                            <input type="number" 
                                   name="portions" 
                                   value="{{ old('portions', $recette->portions) }}"
                                   min="1"
                                   placeholder="4"
                                   class="form-input">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tags alimentaires</label>
                        
                        <div class="tags-grid">
                            @php
                                $currentTags = [];
                                if ($recette->tags) {
                                    $currentTags = is_array($recette->tags) ? $recette->tags : json_decode($recette->tags, true);
                                    if (!is_array($currentTags)) $currentTags = [];
                                }
                            @endphp
                            
                            <label class="checkbox-label">
                                <input type="checkbox" name="tags[]" value="vegetarien" class="checkbox-input" {{ in_array('vegetarien', $currentTags) ? 'checked' : '' }}>
                                <span>Végétarien</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="tags[]" value="vegan" class="checkbox-input" {{ in_array('vegan', $currentTags) ? 'checked' : '' }}>
                                <span>Vegan</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="tags[]" value="sans-gluten" class="checkbox-input" {{ in_array('sans-gluten', $currentTags) ? 'checked' : '' }}>
                                <span>Sans gluten</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="tags[]" value="sans-lactose" class="checkbox-input" {{ in_array('sans-lactose', $currentTags) ? 'checked' : '' }}>
                                <span>Sans lactose</span>
                            </label>
                        </div>
                        
                        <div>
                            <label class="form-label-secondary">Ajouter des tags personnalisés (optionnel)</label>
                            @php
                                $customTags = collect($currentTags)->reject(function($tag) {
                                    return in_array($tag, ['vegetarien', 'vegan', 'sans-gluten', 'sans-lactose']);
                                })->implode(', ');
                            @endphp
                            <input type="text" 
                                   name="tags_custom"
                                   value="{{ old('tags_custom', $customTags) }}"
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
                            Aucun ingrédient ajouté. Tapez "100g de tomate" puis cliquez Ajouter.
                        </div>
                        <div id="ingredients-error" class="error-message" style="display: none;">
                            Vous devez ajouter au moins un ingrédient.
                        </div>
                        @error('ingredients')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <textarea name="ingredients" id="ingredients" style="display: none;" required>{{ old('ingredients', $recette->ingredients) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Instructions de préparation *</label>
                        <textarea name="instructions" 
                                  rows="6"
                                  placeholder="1. Faire bouillir l'eau...&#10;2. Cuire les pâtes...&#10;3. Préparer la sauce..."
                                  class="form-textarea"
                                  required>{{ old('instructions', $recette->instructions) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Image de la recette</label>
                        @if($recette->image)
                            <div class="current-image">
                                <p class="current-image-label">Image actuelle :</p>
                                <img src="{{ Storage::url($recette->image) }}" 
                                     alt="{{ $recette->titre }}" 
                                     class="current-image-preview">
                            </div>
                        @endif
                        <input type="file" 
                               name="image" 
                               accept=".jpg,.jpeg,.png,.webp"
                               class="form-input">
                        <p class="help-text-large">Formats acceptés: JPG, PNG, WEBP (max 2MB)</p>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            Sauvegarder
                        </button>
                        <a href="{{ route('recettes.show', $recette) }}" class="btn-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const existants = `{!! addslashes(old('ingredients', $recette->ingredients)) !!}`;
            chargerIngredientsEdit(existants);
        });
    </script>
</x-app-layout>
