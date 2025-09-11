document.addEventListener('DOMContentLoaded', function() {
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
});


