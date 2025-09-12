<x-app-layout>

    <div class="home-container">
        <div class="home-content">
            <div class="home-welcome">
                <h1 class="home-title">Bienvenue sur LaravelCook</h1>
                <p class="home-subtitle">Gérez vos recettes et découvrez de nouveaux plats en quelques clics</p>
            </div>

            <div class="home-grid">
                <div class="home-card home-card-orange" onclick="window.location.href='{{ route('mes-recettes.index') }}'">
                    <div class="home-card-header">
                        <div style="display: flex; align-items: center;">
                            <span class="home-card-icon">👨‍🍳</span>
                            <h3 class="home-card-title">Mes Recettes</h3>
                        </div>
                        <div class="home-card-arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </div>
                    </div>
                    <p class="home-card-description">
                        Créez, modifiez et organisez vos recettes personnelles. Ajoutez des photos, des ingrédients et partagez vos créations culinaires.
                    </p>
                    <div class="home-card-stats">
                        <span class="home-card-stat">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            Gestion complète
                        </span>
                        <span class="home-card-stat">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            Upload d'images
                        </span>
                    </div>
                </div>

                <div class="home-card home-card-blue" onclick="window.location.href='{{ route('recettes.index') }}'">
                    <div class="home-card-header">
                        <div style="display: flex; align-items: center;">
                            <span class="home-card-icon">📖</span>
                            <h3 class="home-card-title">Catalogue</h3>
                        </div>
                        <div class="home-card-arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </div>
                    </div>
                    <p class="home-card-description">
                        Explorez toutes les recettes de la communauté. Filtrez par type de plat, ingrédients ou tags pour trouver l'inspiration.
                    </p>
                    <div class="home-card-stats">
                        <span class="home-card-stat">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Recherche avancée
                        </span>
                        <span class="home-card-stat">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                                <path d="M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                            Communauté
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
