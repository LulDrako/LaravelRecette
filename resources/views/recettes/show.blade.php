@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/recettes-pages.css') }}">
    
    <div class="show-container">
        <div class="show-back-container">
            <a href="{{ route('recettes.index') }}" class="show-back-button">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Retour aux recettes
            </a>
        </div>

        <div class="show-content-wrapper">
            <div class="show-hero-card">
                @if($recette->image)
                    <div class="show-hero-image" style="background-image: url('{{ Storage::url($recette->image) }}');">
                        <div class="show-hero-overlay">
                            <h1 class="show-hero-title">{{ $recette->titre }}</h1>
                            @if($recette->description)
                                <p class="show-hero-description">{{ $recette->description }}</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="show-hero-no-image">
                        <h1 class="show-hero-title-dark">{{ $recette->titre }}</h1>
                        @if($recette->description)
                            <p class="show-hero-description-dark">{{ $recette->description }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="show-main-grid">
                <div class="show-main-content">
                    <div class="show-section-card">
                        <h2 class="show-section-title">
                            <div class="show-section-icon">
                                <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                                    <path d="M19 7h-3V6a4 4 0 0 0-8 0v1H5a1 1 0 0 0-1 1v11a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V8a1 1 0 0 0-1-1zM10 6a2 2 0 0 1 4 0v1h-4V6zm8 13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V9h2v1a1 1 0 0 0 2 0V9h4v1a1 1 0 0 0 2 0V9h2v10z"/>
                                </svg>
                            </div>
                            Ingrédients
                        </h2>
                        <div class="show-ingredients-list">
                            @php
                                $ingredients = explode("\n", $recette->ingredients);
                            @endphp
                            @foreach($ingredients as $ingredient)
                                @if(trim($ingredient) !== '')
                                    <div class="show-ingredient-item">
                                        <span class="show-ingredient-text">{{ trim($ingredient) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="show-section-card">
                        <h2 class="show-section-title">
                            <div class="show-section-icon">
                                <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </div>
                            Instructions
                        </h2>
                        <div class="show-instructions-text">
                            {!! nl2br(e($recette->instructions)) !!}
                        </div>
                    </div>
                </div>

                <div class="show-sidebar">
                    <div class="show-info-card">
                        <h3 class="show-info-title">Informations</h3>
                        
                        @if($recette->type)
                            <div class="show-info-item">
                                <div class="show-info-icon">
                                    <svg width="16" height="16" fill="#6b7280" viewBox="0 0 24 24">
                                        <path d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="show-info-label">Type</div>
                                    <div class="show-info-value">{{ ucfirst($recette->type) }}</div>
                                </div>
                            </div>
                        @endif

                        @if($recette->temps_preparation)
                            <div class="show-info-item">
                                <div class="show-info-icon">
                                    <svg width="16" height="16" fill="#6b7280" viewBox="0 0 24 24">
                                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6V12L16,14L15,15.5L11,13V6H12Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="show-info-label">Temps</div>
                                    <div class="show-info-value">{{ $recette->temps_preparation }} min</div>
                                </div>
                            </div>
                        @endif

                        @if($recette->portions)
                            <div class="show-info-item">
                                <div class="show-info-icon">
                                    <svg width="16" height="16" fill="#6b7280" viewBox="0 0 24 24">
                                        <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="show-info-label">Portions</div>
                                    <div class="show-info-value">{{ $recette->portions }} pers.</div>
                                </div>
                            </div>
                        @endif

                        <div class="show-info-item show-info-divider">
                            <div class="show-info-icon-primary">
                                <svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-info-label">Créé par</div>
                                <div class="show-info-value">{{ $recette->user->name }}</div>
                            </div>
                        </div>
                    </div>

                    @if($recette->tags && count($recette->tags) > 0)
                        <div class="show-info-card">
                            <h3 class="show-info-title">Tags</h3>
                            <div class="show-tags-container">
                                @foreach($recette->tags as $tag)
                                    @php
                                        $tagLabels = [
                                            'sans-gluten' => 'Sans gluten',
                                            'vegetarien' => 'Végétarien',
                                            'vegan' => 'Vegan',
                                            'sans-lactose' => 'Sans lactose'
                                        ];
                                    @endphp
                                    <span class="show-tag">
                                        {{ $tagLabels[$tag] ?? ucfirst($tag) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($recette->user_id === auth()->id())
                        <div class="show-info-card">
                            <h3 class="show-info-title">Actions</h3>
                            <div class="show-actions-container">
                                <a href="{{ route('recettes.edit', $recette) }}" class="show-action-edit">
                                    Modifier
                                </a>
                                <form action="{{ route('recettes.destroy', $recette) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="show-action-delete">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
