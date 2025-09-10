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
        liste.className = 'ingredients-empty';
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
    }
    
    // Mettre à jour le champ caché
    champCache.value = ingredients.map(ing => `- ${ing}`).join('\n');
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Permettre l'ajout avec Entrée
    const input = document.getElementById('ingredient-input');
    if (input) {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                ajouterIngredient();
            }
        });
    }
    
    // Charger les ingrédients existants pour edit
    const existants = document.getElementById('ingredients');
    if (existants && existants.value) {
        ingredients = existants.value.split('\n')
            .filter(line => line.trim())
            .map(line => line.replace(/^-\s*/, ''));
        mettreAJourAffichage();
    } else {
        mettreAJourAffichage();
    }
});