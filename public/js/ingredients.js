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
            <div class="ingredient-item">
                <span>${ingredient}</span>
                <button type="button" 
                        onclick="supprimerIngredient(${index})"
                        class="ingredient-delete">
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
            <div class="ingredient-item">
                <span>${ingredient}</span>
                <button type="button" 
                        onclick="supprimerIngredient(${index})"
                        class="ingredient-delete">
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