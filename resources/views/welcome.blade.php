<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LaravelCook - Vos meilleures recettes</title>
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-content">
                <a href="/" class="logo">🍽️ LaravelCook</a>
                
                @auth
                    <div class="nav-links">
                        <a href="{{ url('/dashboard') }}" class="btn-primary">
                            Dashboard
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Section Hero -->
    <section class="hero">
        <div class="container">
            <h1>Partagez vos meilleures recettes</h1>
            <p>
                Créez, organisez et partagez vos recettes préférées avec une communauté passionnée de cuisine. 
                Découvrez de nouveaux plats et inspirez-vous !
            </p>
            <div class="hero-buttons">
                @auth
                    <a href="{{ route('recettes.index') }}" class="btn-primary">
                        Voir les recettes
                    </a>
                    <a href="{{ route('recettes.create') }}" class="btn-secondary">
                        Créer une recette
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary">
                        Commencer gratuitement
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary">
                        Se connecter
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Section Fonctionnalités -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Pourquoi choisir notre plateforme ?</h2>
            <p class="section-subtitle">
                Une application simple et efficace pour tous vos besoins culinaires
            </p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h3>Créer facilement</h3>
                    <p>
                        Ajoutez vos recettes en quelques clics avec notre interface simple. 
                        Ingrédients, étapes, temps de préparation - tout est facile !
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🏷️</div>
                    <h3>Organiser avec des tags</h3>
                    <p>
                        Classez vos recettes par type, régime alimentaire, temps de préparation. 
                        Retrouvez facilement ce que vous cherchez !
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🔍</div>
                    <h3>Rechercher et filtrer</h3>
                    <p>
                        Système de recherche avancé pour trouver la recette parfaite selon vos envies 
                        et contraintes alimentaires.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section CTA -->
    <section class="section cta-section">
        <div class="container">
            <h2 class="section-title">Prêt à partager vos recettes ?</h2>
            <p class="section-subtitle">
                Rejoignez notre communauté de passionnés de cuisine dès aujourd'hui !
            </p>
            @guest
                <a href="{{ route('register') }}" class="btn-primary">
                    Créer mon compte gratuitement
                </a>
            @else
                <a href="{{ route('recettes.create') }}" class="btn-primary">
                    Créer ma première recette
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 LaravelCook. Une application pour partager la passion de la cuisine.</p>
        </div>
    </footer>
</body>
</html>