let ingredients = [];

function ajouterIngredient() {
    const input = document.getElementById('ingredient-input');
    
    if (!input) {
        console.warn('Champ ingredient-input non trouvé');
        return;
    }
    
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

let ingredientEnEdition = null;

function modifierIngredient(index) {
    if (ingredientEnEdition !== null) return; // Éviter les conflits
    
    ingredientEnEdition = index;
    const liste = document.getElementById('liste-ingredients');
    const ingredientDiv = liste.children[index];
    
    const texteActuel = ingredients[index];
    ingredientDiv.innerHTML = `
        <input type="text" 
               value="${texteActuel}" 
               id="edit-ingredient-${index}"
               style="flex: 1; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; margin-right: 8px;">
        <button type="button" 
                onclick="validerModification(${index})"
                style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 4px; margin-right: 4px; cursor: pointer;">
            Valider
        </button>
        <button type="button" 
                onclick="annulerModification(${index})"
                style="background: #6b7280; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">
            Annuler
        </button>
    `;
    
    // Focus et sélection du texte
    const input = document.getElementById(`edit-ingredient-${index}`);
    input.focus();
    input.select();
    
    // Valider avec Entrée
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            validerModification(index);
        } else if (e.key === 'Escape') {
            annulerModification(index);
        }
    });
}

function validerModification(index) {
    const input = document.getElementById(`edit-ingredient-${index}`);
    const nouveauTexte = input.value.trim();
    
    if (nouveauTexte !== '') {
        ingredients[index] = nouveauTexte;
    }
    
    ingredientEnEdition = null;
    mettreAJourAffichage();
}

function annulerModification(index) {
    ingredientEnEdition = null;
    mettreAJourAffichage();
}

function mettreAJourAffichage() {
    const liste = document.getElementById('liste-ingredients');
    const champCache = document.getElementById('ingredients');
    const erreurDiv = document.getElementById('ingredients-error');
    
    if (!liste || !champCache) {
        console.warn('Éléments ingrédients non trouvés dans le DOM');
        return;
    }
    
    if (ingredients.length === 0) {
        liste.innerHTML = 'Aucun ingrédient ajouté';
        liste.className = 'ingredients-empty';
        if (erreurDiv) {
            erreurDiv.style.display = 'block';
        }
    } else {
        liste.className = '';
        liste.style.color = '#374151';
        liste.style.textAlign = 'left';
        liste.innerHTML = ingredients.map((ingredient, index) => `
            <div class="ingredient-item" style="display: flex; align-items: center; gap: 8px; padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 4px;">
                <span style="flex: 1; color: #374151;">${ingredient}</span>
                <button type="button" 
                        onclick="modifierIngredient(${index})"
                        style="background: #6b7280; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
                    Modifier
                </button>
                <button type="button" 
                        onclick="supprimerIngredient(${index})"
                        style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
                    Supprimer
                </button>
            </div>
        `).join('');
        
        if (erreurDiv) {
            erreurDiv.style.display = 'none';
        }
    }
    
    champCache.value = ingredients.map(ing => `- ${ing}`).join('\n');
}

function validerIngredients() {
    const erreurDiv = document.getElementById('ingredients-error');
    
    console.log('Validation: nombre d\'ingrédients:', ingredients.length);
    
    if (ingredients.length === 0) {
        console.log('Aucun ingrédient, affichage erreur');
        if (erreurDiv) {
            erreurDiv.style.display = 'block';
        } else {
            console.warn('Element ingredients-error non trouvé');
        }
        return false;
    } else {
        console.log('Ingrédients présents, masquer erreur');
        if (erreurDiv) {
            erreurDiv.style.display = 'none';
        }
        return true;
    }
}

function chargerIngredientsEdit(ingredientsText) {
    console.log('chargerIngredientsEdit appelée avec:', ingredientsText);
    
    if (ingredientsText) {
        ingredients = ingredientsText.split('\n').filter(line => line.trim()).map(line => line.replace(/^-\s*/, ''));
        console.log('Ingrédients chargés:', ingredients);
        mettreAJourAffichageInitial();
    } else {
        console.log('Aucun ingrédient existant, affichage vide');
        mettreAJourAffichageInitial();
    }
    
    const formulaire = document.querySelector('form');
    if (formulaire && !formulaire.hasAttribute('data-validation-added')) {
        console.log('Ajout de la validation au formulaire');
        formulaire.setAttribute('data-validation-added', 'true');
        formulaire.addEventListener('submit', function(e) {
            console.log('Validation des ingrédients, nombre:', ingredients.length);
            if (!validerIngredients()) {
                console.log('Validation échouée, empêcher soumission');
                e.preventDefault();
                return false;
            }
            console.log('Validation réussie');
        });
    }
}

function afficherIngredients() {
    mettreAJourAffichageInitial();
}

function mettreAJourAffichageInitial() {
    const liste = document.getElementById('liste-ingredients');
    const champCache = document.getElementById('ingredients');
    const erreurDiv = document.getElementById('ingredients-error');
    
    if (!liste || !champCache) {
        console.warn('Éléments ingrédients non trouvés dans le DOM');
        return;
    }
    
    if (ingredients.length === 0) {
        liste.innerHTML = 'Aucun ingrédient ajouté';
        liste.className = 'ingredients-empty';
        if (erreurDiv) {
            erreurDiv.style.display = 'none';
        }
    } else {
        liste.className = '';
        liste.style.color = '#374151';
        liste.style.textAlign = 'left';
        liste.innerHTML = ingredients.map((ingredient, index) => `
            <div class="ingredient-item" style="display: flex; align-items: center; gap: 8px; padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 4px;">
                <span style="flex: 1; color: #374151;">${ingredient}</span>
                <button type="button" 
                        onclick="modifierIngredient(${index})"
                        style="background: #6b7280; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
                    Modifier
                </button>
                <button type="button" 
                        onclick="supprimerIngredient(${index})"
                        style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
                    Supprimer
                </button>
            </div>
        `).join('');
        
        if (erreurDiv) {
            erreurDiv.style.display = 'none';
        }
    }
    
    champCache.value = ingredients.map(ing => `- ${ing}`).join('\n');
}

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('ingredient-input');
    if (input) {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                ajouterIngredient();
            }
        });
    }
    
    const existants = document.getElementById('ingredients');
    if (existants && existants.value && ingredients.length === 0) {
        ingredients = existants.value.split('\n')
            .filter(line => line.trim())
            .map(line => line.replace(/^-\s*/, ''));
        mettreAJourAffichage();
    } else if (ingredients.length === 0) {
        mettreAJourAffichage();
    }
    
    const formulaire = document.querySelector('form');
    if (formulaire && !formulaire.hasAttribute('data-validation-added')) {
        formulaire.setAttribute('data-validation-added', 'true');
        formulaire.addEventListener('submit', function(e) {
            if (!validerIngredients()) {
                e.preventDefault();
                return false;
            }
        });
    }
});