<!-- Belle notification de confirmation -->
<div id="beautiful-confirmation" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="confirmation-content">
        <!-- Header rouge -->
        <div class="bg-red-500 rounded-t-2xl p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white">Confirmer la suppression</h3>
        </div>
        
        <!-- Contenu -->
        <div class="p-6">
            <p class="text-gray-700 text-center text-lg mb-6" id="confirmation-message">
                Êtes-vous sûr de vouloir supprimer cette recette ?
            </p>
            
            <!-- Boutons -->
            <div class="flex space-x-4">
                <button id="confirmation-cancel" class="flex-1 px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200 font-semibold">
                    Annuler
                </button>
                <button id="confirmation-delete" class="flex-1 px-6 py-3 text-white bg-red-500 hover:bg-red-600 rounded-xl transition-all duration-200 font-semibold">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('beautiful-confirmation');
    const content = document.getElementById('confirmation-content');
    const message = document.getElementById('confirmation-message');
    const cancelBtn = document.getElementById('confirmation-cancel');
    const deleteBtn = document.getElementById('confirmation-delete');
    
    let currentDeleteUrl = '';
    
    window.showBeautifulConfirmation = function(deleteUrl, customMessage = '') {
        currentDeleteUrl = deleteUrl;
        if (customMessage) {
            message.innerHTML = customMessage;
        } else {
            message.textContent = 'Êtes-vous sûr de vouloir supprimer cette recette ?';
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    };
    
    function hideConfirmation() {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    cancelBtn.addEventListener('click', hideConfirmation);
    deleteBtn.addEventListener('click', function() {
        // Créer un formulaire pour envoyer la requête DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = currentDeleteUrl;
        
        // Ajouter le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Ajouter la méthode DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Ajouter le formulaire au body et le soumettre
        document.body.appendChild(form);
        form.submit();
    });
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            hideConfirmation();
        }
    });
});
</script>
