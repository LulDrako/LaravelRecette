@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>

    <div class="filters-container">
        <form method="GET" action="{{ request()->routeIs('recettes.mes-recettes') ? route('recettes.mes-recettes') : route('recettes.index') }}" class="filters-form">
            <!-- Ligne principale avec tous les inputs -->
            <div class="filters-main-row">
                <div class="filter-group">
                    <label for="search" class="filter-label">Rechercher</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Titre, description, ingrédient..."
                           class="filter-input">
                </div>

                <div class="filter-group">
                    <label for="type" class="filter-label">Type de plat</label>
                    <select id="type" name="type" class="filter-select">
                        <option value="">Tous les types</option>
                        <option value="entree" {{ request('type') === 'entree' ? 'selected' : '' }}>Entrée</option>
                        <option value="plat" {{ request('type') === 'plat' ? 'selected' : '' }}>Plat principal</option>
                        <option value="dessert" {{ request('type') === 'dessert' ? 'selected' : '' }}>Dessert</option>
                        <option value="apero" {{ request('type') === 'apero' ? 'selected' : '' }}>Apéritif</option>
                        <option value="boisson" {{ request('type') === 'boisson' ? 'selected' : '' }}>Boisson</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="temps_max" class="filter-label">Temps max (minutes)</label>
                    <input type="number" 
                           id="temps_max" 
                           name="temps_max" 
                           value="{{ request('temps_max') }}"
                           placeholder="Ex: 30"
                           class="filter-input">
                </div>

                <div class="filter-group tags-group">
                    <label class="filter-label">Tags alimentaires</label>
                    <div class="filter-tags">
                        <label class="filter-tag">
                            <input type="checkbox" name="tags[]" value="vegetarien" 
                                   {{ in_array('vegetarien', request('tags', [])) ? 'checked' : '' }}>
                            <span>Végétarien</span>
                        </label>
                        <label class="filter-tag">
                            <input type="checkbox" name="tags[]" value="vegan" 
                                   {{ in_array('vegan', request('tags', [])) ? 'checked' : '' }}>
                            <span>Vegan</span>
                        </label>
                        <label class="filter-tag">
                            <input type="checkbox" name="tags[]" value="sans-gluten" 
                                   {{ in_array('sans-gluten', request('tags', [])) ? 'checked' : '' }}>
                            <span>Sans gluten</span>
                        </label>
                        <label class="filter-tag">
                            <input type="checkbox" name="tags[]" value="sans-lactose" 
                                   {{ in_array('sans-lactose', request('tags', [])) ? 'checked' : '' }}>
                            <span>Sans lactose</span>
                        </label>
                    </div>
                </div>

                <div class="filter-group">
                    <label for="tags_custom" class="filter-label">Tags personnalisés</label>
                    <input type="text" 
                           id="tags_custom" 
                           name="tags_custom" 
                           value="{{ request('tags_custom') }}"
                           placeholder="Ex: rapide, facile, économique..."
                           class="filter-input">
                </div>

                <!-- Boutons à droite -->
                <div class="filter-group buttons-group">
                    <a href="{{ request()->routeIs('recettes.mes-recettes') ? route('recettes.mes-recettes') : route('recettes.index') }}" 
                       class="filter-btn filter-btn-reset">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                        </svg>
                        Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="index-container">
        <div class="index-max-width">
            @if (session('success'))
                <div class="index-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($recettes->count() > 0)
                @if(($pageTitle ?? '') === 'Mes recettes')
                    <div class="index-create-button-container">
                        <a href="{{ route('recettes.create') }}" class="index-create-button">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                            </svg>
                            Créer une nouvelle recette
                        </a>
                    </div>
                @endif
                
                <div class="index-grid">
                    @foreach($recettes as $recette)
                        <div class="index-card">
                            <div class="index-card-image">
                                @if($recette->image)
                                    <img src="{{ Storage::url($recette->image) }}" alt="{{ $recette->titre }}">
                                @else
                                    <div class="index-card-placeholder">
                                        <svg width="48" height="48" fill="#9ca3af" viewBox="0 0 24 24">
                                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                @if($recette->type)
                                    <div class="index-card-type">
                                        @php
                                            $typeLabels = [
                                                'entree' => 'Entrée',
                                                'plat' => 'Plat principal',
                                                'dessert' => 'Dessert',
                                                'apero' => 'Apéritif',
                                                'boisson' => 'Boisson'
                                            ];
                                        @endphp
                                        {{ $typeLabels[$recette->type] ?? ucfirst($recette->type) }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="index-card-content">
                                <h3 class="index-card-title">{{ $recette->titre }}</h3>
                                
                                <div class="index-card-meta">
                                    @if($recette->temps_preparation)
                                        <div class="index-card-meta-item">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6V12L16,14L15,15.5L11,13V6H12Z"/>
                                            </svg>
                                            {{ $recette->temps_preparation }} min
                                        </div>
                                    @endif
                                    @if($recette->portions)
                                        <div class="index-card-meta-item">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                <circle cx="5" cy="7" r="3.5"/>
                                                <circle cx="12" cy="7" r="3.5"/>
                                                <circle cx="19" cy="7" r="3.5"/>
                                                <path d="M5 12c-2.5 0-5 1.2-5 3v2h10v-2c0-1.8-2.5-3-5-3z"/>
                                                <path d="M12 12c-2.5 0-5 1.2-5 3v2h10v-2c0-1.8-2.5-3-5-3z"/>
                                                <path d="M19 12c-2.5 0-5 1.2-5 3v2h10v-2c0-1.8-2.5-3-5-3z"/>
                                            </svg>
                                            {{ $recette->portions }} pers.
                                        </div>
                                    @endif
                                </div>
                                
                                @php 
                                    $tags = is_array($recette->tags) ? $recette->tags : (json_decode($recette->tags) ?? []);
                                @endphp
                                @if($recette->tags && count($tags) > 0)
                                    <div class="index-card-tags">
                                        @foreach($tags as $tag)
                                            @php
                                                $tagLabels = [
                                                    'sans-gluten' => 'Sans gluten',
                                                    'vegetarien' => 'Végétarien',
                                                    'vegan' => 'Vegan',
                                                    'sans-lactose' => 'Sans lactose'
                                                ];
                                            @endphp
                                            <span class="index-card-tag">
                                                {{ $tagLabels[$tag] ?? ucfirst($tag) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="mt-auto">
                                    @if(($pageTitle ?? '') === 'Mes recettes')
                                        <div class="index-card-meta index-card-meta-creation">
                                            <div class="index-card-meta-item">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M5,6H19V5H5V6Z"/>
                                                </svg>
                                                Créée le {{ $recette->created_at->format('d/m/Y à H:i') }}
                                            </div>
                                            @if($recette->updated_at != $recette->created_at)
                                                <div class="index-card-meta-item index-card-meta-updated">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="3"/>
                                                    </svg>
                                                    Mod. {{ $recette->updated_at->format('d/m/Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="index-card-meta index-card-meta-creation">
                                            <div class="index-card-meta-item">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                                                </svg>
                                                {{ $recette->user->name }}
                                            </div>
                                            
                                            @if($recette->updated_at != $recette->created_at)
                                                <div class="index-card-meta-item">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="3"/>
                                                    </svg>
                                                    Modifiée le {{ $recette->updated_at->format('d/m/Y à H:i') }}
                                                </div>
                                            @else
                                                <div class="index-card-meta-item">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M5,6H19V5H5V6Z"/>
                                                    </svg>
                                                    Créée le {{ $recette->created_at->format('d/m/Y à H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="index-card-actions">
                                    <a href="{{ route('recettes.show', $recette) }}" class="index-card-view">
                                        Voir la recette
                                    </a>
                                    
                                    @if($recette->user_id === auth()->id())
                                        <form action="{{ route('recettes.destroy', $recette) }}" method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?');" 
                                              class="index-card-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="index-card-delete">
                                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="index-empty">
                    <div class="index-empty-icon">🍽️</div>
                    @if(($pageTitle ?? '') === 'Mes recettes')
                        <h3>Aucune recette dans votre collection</h3>
                        <p>Commencez par créer votre première recette !</p>
                        <div class="empty-create-container">
                            <a href="{{ route('recettes.create') }}" class="empty-create-link">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" class="empty-create-svg">
                                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                                </svg>
                                Créer ma première recette
                            </a>
                        </div>
                    @else
                        <h3>Aucune recette dans le catalogue</h3>
                        <p class="empty-subtitle">Essayez de modifier vos filtres de recherche ou revenez plus tard !</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        // Activer la recherche automatique
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof activerRechercheAutomatique === 'function') {
                activerRechercheAutomatique();
            }
            if (typeof ameliorerRechercheTags === 'function') {
                ameliorerRechercheTags();
            }
        });
    </script>
</x-app-layout>