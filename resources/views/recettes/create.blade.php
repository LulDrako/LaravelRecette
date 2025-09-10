<x-app-layout>
    <div style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 40px 20px;">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">
                    Créer une nouvelle recette
                </h1>
                <p style="color: #6b7280; font-size: 16px;">
                    Partagez votre recette avec la communauté
                </p>
            </div>
            <div style="background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);">
                <form action="{{ route('recettes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                               value="{{ old('titre') }}"
                               placeholder="Ex: Spaghetti à la carbonara"
                               style="width: 100%; 
                                      padding: 16px; 
                                      border: 2px solid #e5e7eb; 
                                      border-radius: 12px; 
                                      font-size: 16px;
                                      transition: all 0.3s ease;"
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
                                  style="width: 100%; 
                                         padding: 16px; 
                                         border: 2px solid #e5e7eb; 
                                         border-radius: 12px; 
                                         font-size: 16px;
                                         resize: vertical;
                                         transition: all 0.3s ease;"
                                  onfocus="this.style.borderColor='#1f2937'"
                                  onblur="this.style.borderColor='#e5e7eb'"
                                  required>{{ old('description') }}</textarea>
                    </div>


                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px;">
                        <div>
                            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                                Type de plat
                            </label>
                            <select name="type"
                                    style="width: 100%; 
                                           padding: 16px; 
                                           border: 2px solid #e5e7eb; 
                                           border-radius: 12px; 
                                           font-size: 16px;
                                           background: white;
                                           transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='#1f2937'"
                                    onblur="this.style.borderColor='#e5e7eb'">
                                <option value="">-- Choisir --</option>
                                <option value="entree" {{ old('type') == 'entree' ? 'selected' : '' }}>Entrée</option>
                                <option value="plat" {{ old('type') == 'plat' ? 'selected' : '' }}>Plat principal</option>
                                <option value="dessert" {{ old('type') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                                <option value="apero" {{ old('type') == 'apero' ? 'selected' : '' }}>Apéritif</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                                Temps (minutes)
                            </label>
                            <input type="number" 
                                   name="temps_preparation" 
                                   value="{{ old('temps_preparation') }}"
                                   min="1"
                                   placeholder="30"
                                   style="width: 100%; 
                                          padding: 16px; 
                                          border: 2px solid #e5e7eb; 
                                          border-radius: 12px; 
                                          font-size: 16px;
                                          transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#1f2937'"
                                   onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                                Portions
                            </label>
                            <input type="number" 
                                   name="portions" 
                                   value="{{ old('portions', 4) }}"
                                   min="1"
                                   placeholder="4"
                                   style="width: 100%; 
                                          padding: 16px; 
                                          border: 2px solid #e5e7eb; 
                                          border-radius: 12px; 
                                          font-size: 16px;
                                          transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#1f2937'"
                                   onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                    </div>


                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 12px; font-size: 15px;">
                            Tags alimentaires
                        </label>
                        
    
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 16px;">
                            <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;"
                                   onmouseover="this.style.borderColor='#d1d5db'"
                                   onmouseout="this.style.borderColor='#e5e7eb'">
                                <input type="checkbox" name="tags[]" value="vegetarien" style="width: 18px; height: 18px;">
                                <span>Végétarien</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;"
                                   onmouseover="this.style.borderColor='#d1d5db'"
                                   onmouseout="this.style.borderColor='#e5e7eb'">
                                <input type="checkbox" name="tags[]" value="vegan" style="width: 18px; height: 18px;">
                                <span>Vegan</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;"
                                   onmouseover="this.style.borderColor='#d1d5db'"
                                   onmouseout="this.style.borderColor='#e5e7eb'">
                                <input type="checkbox" name="tags[]" value="sans-gluten" style="width: 18px; height: 18px;">
                                <span>Sans gluten</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;"
                                   onmouseover="this.style.borderColor='#d1d5db'"
                                   onmouseout="this.style.borderColor='#e5e7eb'">
                                <input type="checkbox" name="tags[]" value="sans-lactose" style="width: 18px; height: 18px;">
                                <span>Sans lactose</span>
                            </label>
                        </div>
                        
    
                        <div>
                            <label style="display: block; font-weight: 500; color: #6b7280; margin-bottom: 8px; font-size: 14px;">
                                Ajouter des tags personnalisés (optionnel)
                            </label>
                            <input type="text" 
                                   name="tags_custom"
                                   placeholder="Ex: rapide, facile, économique (séparez par des virgules)"
                                   style="width: 100%; 
                                          padding: 12px; 
                                          border: 2px solid #e5e7eb; 
                                          border-radius: 12px; 
                                          font-size: 14px;
                                          transition: all 0.3s ease;"
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
                                   style="flex: 1; 
                                          padding: 16px; 
                                          border: 2px solid #e5e7eb; 
                                          border-radius: 12px; 
                                          font-size: 16px;
                                          transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#1f2937'"
                                   onblur="this.style.borderColor='#e5e7eb'">
                            <button type="button" 
                                    onclick="ajouterIngredient()"
                                    style="background: #1f2937; 
                                           color: white; 
                                           border: none; 
                                           padding: 16px 24px; 
                                           border-radius: 12px; 
                                           font-weight: 600;
                                           cursor: pointer;
                                           transition: all 0.3s ease;"
                                    onmouseover="this.style.background='#374151'"
                                    onmouseout="this.style.background='#1f2937'">
                                Ajouter
                            </button>
                        </div>
                        <div id="liste-ingredients" style="min-height: 60px; border: 2px dashed #d1d5db; border-radius: 12px; padding: 20px; color: #6b7280; text-align: center;">
                            Aucun ingrédient ajouté
                        </div>
                        <textarea id="ingredients" name="ingredients" style="display: none;" required>{{ old('ingredients') }}</textarea>
                    </div>


                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                            Instructions *
                        </label>
                        <textarea name="instructions" 
                                  rows="6"
                                  placeholder="1. Faire bouillir l'eau...&#10;2. Cuire les pâtes...&#10;3. Préparer la sauce..."
                                  style="width: 100%; 
                                         padding: 16px; 
                                         border: 2px solid #e5e7eb; 
                                         border-radius: 12px; 
                                         font-size: 16px;
                                         resize: vertical;
                                         transition: all 0.3s ease;"
                                  onfocus="this.style.borderColor='#1f2937'"
                                  onblur="this.style.borderColor='#e5e7eb'"
                                  required>{{ old('instructions') }}</textarea>
                    </div>


                    <div style="margin-bottom: 32px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                            Photo (optionnel)
                        </label>
                        <input type="file" 
                               name="image" 
                               accept=".jpg,.jpeg,.png,.webp"
                               style="width: 100%; 
                                      padding: 16px; 
                                      border: 2px solid #e5e7eb; 
                                      border-radius: 12px; 
                                      font-size: 16px;
                                      transition: all 0.3s ease;"
                               onfocus="this.style.borderColor='#1f2937'"
                               onblur="this.style.borderColor='#e5e7eb'">
                        <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">
                            Formats acceptés: JPG, PNG, WEBP (max 2MB)
                        </p>
                    </div>


                    <div style="display: flex; gap: 16px; justify-content: center;">
                        <a href="{{ route('recettes.index') }}" 
                           style="background: #6b7280; 
                                  color: white; 
                                  padding: 16px 32px; 
                                  border-radius: 12px; 
                                  text-decoration: none; 
                                  font-weight: 600;
                                  transition: all 0.3s ease;"
                           onmouseover="this.style.background='#4b5563'"
                           onmouseout="this.style.background='#6b7280'">
                            Annuler
                        </a>
                        <button type="submit" 
                                style="background: #1f2937; 
                                       color: white; 
                                       border: none; 
                                       padding: 16px 32px; 
                                       border-radius: 12px; 
                                       font-weight: 600;
                                       cursor: pointer;
                                       transition: all 0.3s ease;"
                                onmouseover="this.style.background='#374151'"
                                onmouseout="this.style.background='#1f2937'">
                            Créer la recette
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let ingredients = [];

        function ajouterIngredient() {
            const input = document.getElementById('ingredient-input');
            const texte = input.value.trim();
            
            if (texte === '') {
                alert('Veuillez saisir un ingrédient');
                return;
            }
            
            ingredients.push(texte);
            input.value = '';
            mettreAJourAffichage();
        }

        function supprimerIngredient(index) {
            ingredients.splice(index, 1);
            mettreAJourAffichage();
        }

        function mettreAJourAffichage() {
            const liste = document.getElementById('liste-ingredients');
            const champCache = document.getElementById('ingredients');
            
            if (ingredients.length === 0) {
                liste.innerHTML = 'Aucun ingrédient ajouté';
                liste.style.color = '#6b7280';
                liste.style.textAlign = 'center';
            } else {
                liste.style.color = '#374151';
                liste.style.textAlign = 'left';
                liste.innerHTML = ingredients.map((ingredient, index) => `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f9fafb; border-radius: 8px; margin-bottom: 8px;">
                        <span>${ingredient}</span>
                        <button type="button" 
                                onclick="supprimerIngredient(${index})"
                                style="background: #ef4444; color: white; border: none; padding: 4px 8px; border-radius: 6px; cursor: pointer; font-size: 12px;"
                                onmouseover="this.style.background='#dc2626'"
                                onmouseout="this.style.background='#ef4444'">
                            Supprimer
                        </button>
                    </div>
                `).join('');
            }
            
            champCache.value = ingredients.map(ing => `- ${ing}`).join('\n');
        }

        // Permettre l'ajout avec Entrée
        document.getElementById('ingredient-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                ajouterIngredient();
            }
        });
    </script>
</x-app-layout>
