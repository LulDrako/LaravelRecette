@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <div style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 40px 20px;">
        <div style="max-width: 800px; margin: 0 auto;">
            

            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">
                    Modifier la recette
                </h1>
                <p style="color: #6b7280; font-size: 16px;">
                    {{ $recette->titre }}
                </p>
            </div>


            <div style="background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);">
                <form action="{{ route('recettes.update', $recette) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
        
                    @if ($errors->any())
                        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                            <ul style="margin: 0; padding-left: 20px; color: #dc2626;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

        
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                            Titre de la recette *
                        </label>
                        <input type="text" 
                               name="titre" 
                               value="{{ old('titre', $recette->titre) }}"
                               placeholder="Ex: Spaghetti à la carbonara"
                               style="width: 100%; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; transition: all 0.3s ease;"
                               onfocus="this.style.borderColor='#1f2937'"
                               onblur="this.style.borderColor='#e5e7eb'"
                               required>
                    </div>

        
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                            Description *
                        </label>
                        <textarea name="description" 
                                  rows="3"
                                  placeholder="Décrivez votre recette en quelques mots..."
                                  style="width: 100%; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; resize: vertical; transition: all 0.3s ease;"
                                  onfocus="this.style.borderColor='#1f2937'"
                                  onblur="this.style.borderColor='#e5e7eb'"
                                  required>{{ old('description', $recette->description) }}</textarea>
                    </div>

        
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px;">
                        <div>
                            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                                Type de plat
                            </label>
                            <select name="type"
                                    style="width: 100%; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; background: white; transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='#1f2937'"
                                    onblur="this.style.borderColor='#e5e7eb'">
                                <option value="">-- Choisir --</option>
                                <option value="Entrée" {{ old('type', $recette->type) == 'Entrée' ? 'selected' : '' }}>Entrée</option>
                                <option value="Plat principal" {{ old('type', $recette->type) == 'Plat principal' ? 'selected' : '' }}>Plat principal</option>
                                <option value="Dessert" {{ old('type', $recette->type) == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                                <option value="Apéritif" {{ old('type', $recette->type) == 'Apéritif' ? 'selected' : '' }}>Apéritif</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                                Temps (minutes)
                            </label>
                            <input type="number" 
                                   name="temps_preparation" 
                                   value="{{ old('temps_preparation', $recette->temps_preparation) }}"
                                   min="1"
                                   placeholder="30"
                                   style="width: 100%; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#1f2937'"
                                   onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                                Portions
                            </label>
                            <input type="number" 
                                   name="portions" 
                                   value="{{ old('portions', $recette->portions) }}"
                                   min="1"
                                   placeholder="4"
                                   style="width: 100%; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#1f2937'"
                                   onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                    </div>

        
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 12px; font-size: 15px;">
                            Tags alimentaires
                        </label>
                        
            
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 16px;">
                            @php
                                $currentTags = [];
                                if ($recette->tags) {
                                    $currentTags = is_array($recette->tags) ? $recette->tags : json_decode($recette->tags, true);
                                    if (!is_array($currentTags)) $currentTags = [];
                                }
                            @endphp
                            
                            <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;"
                                   onmouseover="this.style.borderColor='#d1d5db'"
                                   onmouseout="this.style.borderColor='#e5e7eb'">
                                <input type="checkbox" name="tags[]" value="vegetarien" style="width: 18px; height: 18px;" {{ in_array('vegetarien', $currentTags) ? 'checked' : '' }}>
                                <span>Végétarien</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;"
                                   onmouseover="this.style.borderColor='#d1d5db'"
                                   onmouseout="this.style.borderColor='#e5e7eb'">
                                <input type="checkbox" name="tags[]" value="vegan" style="width: 18px; height: 18px;" {{ in_array('vegan', $currentTags) ? 'checked' : '' }}>
                                <span>Vegan</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;"
                                   onmouseover="this.style.borderColor='#d1d5db'"
                                   onmouseout="this.style.borderColor='#e5e7eb'">
                                <input type="checkbox" name="tags[]" value="sans-gluten" style="width: 18px; height: 18px;" {{ in_array('sans-gluten', $currentTags) ? 'checked' : '' }}>
                                <span>Sans gluten</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;"
                                   onmouseover="this.style.borderColor='#d1d5db'"
                                   onmouseout="this.style.borderColor='#e5e7eb'">
                                <input type="checkbox" name="tags[]" value="sans-lactose" style="width: 18px; height: 18px;" {{ in_array('sans-lactose', $currentTags) ? 'checked' : '' }}>
                                <span>Sans lactose</span>
                            </label>
                        </div>
                        
            
                        <div>
                            <label style="display: block; font-weight: 500; color: #6b7280; margin-bottom: 8px; font-size: 14px;">
                                Ajouter des tags personnalisés (optionnel)
                            </label>
                            @php
                                $customTags = collect($currentTags)->reject(function($tag) {
                                    return in_array($tag, ['vegetarien', 'vegan', 'sans-gluten', 'sans-lactose']);
                                })->implode(', ');
                            @endphp
                            <input type="text" 
                                   name="tags_custom"
                                   value="{{ old('tags_custom', $customTags) }}"
                                   placeholder="Ex: rapide, facile, économique (séparez par des virgules)"
                                   style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#1f2937'"
                                   onblur="this.style.borderColor='#e5e7eb'">
                            <p style="color: #6b7280; font-size: 12px; margin-top: 6px;">
                                Tapez vos tags séparés par des virgules (ex: épicé, familial, festif)
                            </p>
                        </div>
                    </div>

        
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                            Ingrédients *
                        </label>
                        <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                            <input type="text" 
                                   id="ingredient-input"
                                   placeholder="Ex: 100g de tomate, 2 oignons..."
                                   style="flex: 1; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#1f2937'"
                                   onblur="this.style.borderColor='#e5e7eb'">
                            <button type="button" 
                                    onclick="ajouterIngredient()"
                                    style="background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: white; padding: 16px 24px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                                Ajouter
                            </button>
                        </div>
                        <div id="ingredients-liste" style="margin-bottom: 16px;"></div>
                        <div id="ingredients-vide" style="text-align: center; padding: 20px; color: #6b7280; background: #f9fafb; border: 2px dashed #d1d5db; border-radius: 12px;">
                            Aucun ingrédient ajouté. Tapez "100g de tomate" puis cliquez Ajouter.
                        </div>
                        <textarea name="ingredients" id="ingredients-cache" style="display: none;" required>{{ old('ingredients', $recette->ingredients) }}</textarea>
                    </div>

        
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                            Instructions de préparation *
                        </label>
                        <textarea name="instructions" 
                                  rows="8"
                                  placeholder="Décrivez les étapes de préparation étape par étape..."
                                  style="width: 100%; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; resize: vertical; transition: all 0.3s ease;"
                                  onfocus="this.style.borderColor='#1f2937'"
                                  onblur="this.style.borderColor='#e5e7eb'"
                                  required>{{ old('instructions', $recette->instructions) }}</textarea>
                    </div>

        
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                            Image de la recette
                        </label>
                        @if($recette->image)
                            <div style="margin-bottom: 16px;">
                                <p style="color: #6b7280; margin-bottom: 8px; font-size: 14px;">Image actuelle :</p>
                                <img src="{{ Storage::url($recette->image) }}" 
                                     alt="{{ $recette->titre }}" 
                                     style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px;">
                            </div>
                        @endif
                        <input type="file" 
                               name="image" 
                               accept="image/*"
                               style="width: 100%; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; transition: all 0.3s ease;"
                               onfocus="this.style.borderColor='#1f2937'"
                               onblur="this.style.borderColor='#e5e7eb'">
                        <p style="color: #6b7280; font-size: 12px; margin-top: 8px;">JPG, PNG, WEBP • Max 2MB</p>
                    </div>

        
                    <div style="display: flex; gap: 16px; justify-content: center; margin-top: 32px;">
                        <button type="submit" 
                                style="background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: white; padding: 16px 32px; border: none; border-radius: 12px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s ease;">
                            Sauvegarder
                        </button>
                        <a href="{{ route('recettes.show', $recette) }}" 
                           style="background: #f9fafb; color: #374151; padding: 16px 32px; border: 2px solid #e5e7eb; border-radius: 12px; font-weight: 600; font-size: 16px; text-decoration: none; transition: all 0.3s ease;">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript minimal -->
    <script>
        let ingredients = [];
        
        function ajouterIngredient() {
            const input = document.getElementById('ingredient-input');
            const valeur = input.value.trim();
            
            if (valeur === '') {
                alert('Veuillez saisir un ingrédient');
                return;
            }
            
            ingredients.push(valeur);
            input.value = '';
            afficherIngredients();
            mettreAJourCache();
        }
        
        function supprimerIngredient(index) {
            ingredients.splice(index, 1);
            afficherIngredients();
            mettreAJourCache();
        }
        
        function afficherIngredients() {
            const liste = document.getElementById('ingredients-liste');
            const vide = document.getElementById('ingredients-vide');
            
            if (ingredients.length === 0) {
                liste.innerHTML = '';
                vide.style.display = 'block';
            } else {
                vide.style.display = 'none';
                liste.innerHTML = ingredients.map((ingredient, index) => `
                    <div style="background: white; border: 2px solid #f3f4f6; border-radius: 12px; padding: 12px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #374151;">${ingredient}</span>
                        <button type="button" onclick="supprimerIngredient(${index})" style="background: #dc2626; color: white; padding: 6px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer;">
                            Supprimer
                        </button>
                    </div>
                `).join('');
            }
        }
        
        function mettreAJourCache() {
            document.getElementById('ingredients-cache').value = ingredients.map(ing => `- ${ing}`).join('\n');
        }
        
        // Charger ingrédients existants
        document.addEventListener('DOMContentLoaded', function() {
            const existants = `{!! addslashes(old('ingredients', $recette->ingredients)) !!}`;
            if (existants) {
                ingredients = existants.split('\n').filter(line => line.trim()).map(line => line.replace(/^-\s*/, ''));
                afficherIngredients();
            } else {
                afficherIngredients();
            }
        });
        
        // Entrée pour ajouter
        document.getElementById('ingredient-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                ajouterIngredient();
            }
        });
    </script>
</x-app-layout>
