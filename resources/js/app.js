import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ========================================
// FONCTIONS POUR LES INGRÉDIENTS
// ========================================

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
    
    // Vérifier si on est sur une page avec des ingrédients
    if (!erreurDiv) {
        return true; // Pas de validation nécessaire sur cette page
    }
    
    console.log('Validation: nombre d\'ingrédients:', ingredients.length);
    
    if (ingredients.length === 0) {
        console.log('Aucun ingrédient, affichage erreur');
        erreurDiv.style.display = 'block';
        return false;
    } else {
        console.log('Ingrédients présents, masquer erreur');
        erreurDiv.style.display = 'none';
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

// ========================================
// FONCTIONS POUR LES INSTRUCTIONS
// ========================================

function initialiserInstructions() {
    const textarea = document.querySelector('textarea[name="instructions"]');
    if (!textarea) return;

    // Si vide au focus/saisie, préfixer avec "1. "
    const ensureFirstNumber = () => {
        if (textarea.value.trim() === '') {
            textarea.value = '1. ';
            // placer le curseur en fin
            const len = textarea.value.length;
            textarea.setSelectionRange(len, len);
        }
    };

    textarea.addEventListener('focus', ensureFirstNumber);
    textarea.addEventListener('input', function() {
        // Si l'utilisateur efface tout, réinsérer "1. " dès qu'il retape
        if (textarea.value === '') return; // laisser vide si l'utilisateur a tout effacé volontairement
        const lines = textarea.value.split(/\r?\n/);
        if (lines.length === 1 && lines[0].trim() !== '' && !/^\d+\.[\s\t]+/.test(lines[0])) {
            // Préfixer la première ligne si non numérotée
            const start = textarea.selectionStart;
            textarea.value = '1. ' + lines[0];
            const delta = 3; // longueur de "1. "
            textarea.setSelectionRange(start + delta, start + delta);
        }
    });

    textarea.addEventListener('keydown', function(e) {
        if (e.key !== 'Enter') return;

        const value = textarea.value;
        const start = textarea.selectionStart;
        const before = value.slice(0, start);
        const after = value.slice(start);

        const lignes = before.split(/\r?\n/);
        let numero = 1;
        for (let i = 0; i < lignes.length; i++) {
            const l = lignes[i].trim();
            if (l.length === 0) continue;
            const m = l.match(/^(\d+)\./);
            if (m) {
                numero = parseInt(m[1], 10) + 1;
            } else {
                // Si la ligne courante n'était pas numérotée, on ne change rien ici
            }
        }

        // Empêcher le retour à la ligne par défaut et insérer "\n{numero}. "
        e.preventDefault();
        const insertion = "\n" + numero + ". ";
        textarea.value = before + insertion + after;
        const caret = start + insertion.length;
        textarea.setSelectionRange(caret, caret);
    });
}

// ========================================
// INITIALISATION
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les ingrédients
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
    
    // Initialiser les instructions
    initialiserInstructions();
});

// Recherche dynamique en temps réel (sans recharger la page)
function activerRechercheAutomatique() {
    const form = document.querySelector('form[method="GET"]');
    if (!form) return;

    // Récupérer tous les champs de recherche
    const searchInput = document.querySelector('input[name="search"]');
    const typeSelect = document.querySelector('select[name="type"]');
    const tempsMaxInput = document.querySelector('input[name="temps_max"]');
    const tagsCheckboxes = document.querySelectorAll('input[name="tags[]"]');
    const tagsCustomInput = document.querySelector('input[name="tags_custom"]');
    const resultsContainer = document.querySelector('.index-container');

    // Fonction pour déclencher la recherche dynamique
    function declencherRecherche() {
        // Afficher un indicateur de chargement
        if (resultsContainer) {
            resultsContainer.innerHTML = `
                <div class="loading-container" style="text-align: center; padding: 2rem;">
                    <div class="loading-spinner" style="display: inline-block; width: 2rem; height: 2rem; border: 3px solid #f3f3f3; border-top: 3px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="margin-top: 1rem; color: #666;">Recherche en cours...</p>
                </div>
                <style>
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                </style>
            `;
        }

        // Construire l'URL avec les paramètres actuels
        const url = new URL(window.location);
        
        // Vider les paramètres existants
        url.search = '';
        
        // Ajouter les nouveaux paramètres
        if (searchInput && searchInput.value.trim()) {
            url.searchParams.set('search', searchInput.value.trim());
        }
        
        if (typeSelect && typeSelect.value) {
            url.searchParams.set('type', typeSelect.value);
        }
        
        if (tempsMaxInput && tempsMaxInput.value.trim()) {
            url.searchParams.set('temps_max', tempsMaxInput.value.trim());
        }
        
        // Ajouter les tags cochés
        const tagsSelectionnes = Array.from(tagsCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
        
        if (tagsSelectionnes.length > 0) {
            tagsSelectionnes.forEach(tag => {
                url.searchParams.append('tags[]', tag);
            });
        }
        
        if (tagsCustomInput && tagsCustomInput.value.trim()) {
            url.searchParams.set('tags_custom', tagsCustomInput.value.trim());
        }

        // Mettre à jour l'URL dans le navigateur sans recharger
        window.history.pushState({}, '', url.toString());

        // Faire la recherche AJAX
        fetch(url.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parser la réponse HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Récupérer le contenu des résultats
            const newResults = doc.querySelector('.index-container');
            
            if (newResults && resultsContainer) {
                // Remplacer le contenu des résultats avec une animation fluide
                resultsContainer.style.opacity = '0.7';
                setTimeout(() => {
                    resultsContainer.innerHTML = newResults.innerHTML;
                    resultsContainer.style.opacity = '1';
                }, 150);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la recherche:', error);
            if (resultsContainer) {
                resultsContainer.innerHTML = `
                    <div class="error-container" style="text-align: center; padding: 2rem;">
                        <p style="color: #e74c3c;">Erreur lors de la recherche. Veuillez réessayer.</p>
                    </div>
                `;
            }
        });
    }

    // Ajouter les événements
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(declencherRecherche, 300); // Attendre 300ms après la dernière frappe
        });
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', declencherRecherche);
    }

    if (tempsMaxInput) {
        let timeout;
        tempsMaxInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(declencherRecherche, 300);
        });
    }

    tagsCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', declencherRecherche);
    });

    if (tagsCustomInput) {
        let timeout;
        tagsCustomInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(declencherRecherche, 300);
        });
    }
}

// Améliorer la recherche de tags personnalisés
function ameliorerRechercheTags() {
    const tagsInput = document.getElementById('tags_custom');
    if (!tagsInput) return;

    // Ne pas normaliser automatiquement - laisser l'utilisateur taper comme il veut
    // La recherche côté serveur gère déjà les accents
}

// Exposer les fonctions globalement pour les vues Blade
window.ajouterIngredient = ajouterIngredient;
window.supprimerIngredient = supprimerIngredient;
window.modifierIngredient = modifierIngredient;
window.validerModification = validerModification;
window.annulerModification = annulerModification;
window.chargerIngredientsEdit = chargerIngredientsEdit;
window.ameliorerRechercheTags = ameliorerRechercheTags;
window.activerRechercheAutomatique = activerRechercheAutomatique;
window.afficherIngredients = afficherIngredients;