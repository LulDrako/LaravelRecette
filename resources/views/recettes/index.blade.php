@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $pageTitle ?? 'Recettes' }}
            </h2>
            <a href="{{ route('recettes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Nouvelle Recette</span>
            </a>
        </div>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/recettes-pages.css') }}">

    <div class="index-container">
        <div class="index-max-width">
            <div class="index-header">
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
                                                    'apero' => 'Apéritif'
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
                                                    <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
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
                                    
                                    <!-- Informations de création - seulement pour "Mes recettes" -->
                                    @if(($pageTitle ?? '') === 'Mes recettes')
                                        <div class="index-card-meta index-card-meta-creation">
                                            <!-- Toujours afficher la date de création -->
                                            <div class="index-card-meta-item">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M5,6H19V5H5V6Z"/>
                                                </svg>
                                                Créée le {{ $recette->created_at->format('d/m/Y à H:i') }}
                                            </div>
                                            <!-- Afficher la date de modification si elle existe -->
                                            @if($recette->updated_at != $recette->created_at)
                                                <div class="index-card-meta-item index-card-meta-updated">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="3"/>
                                                    </svg>
                                                    Modifiée le {{ $recette->updated_at->format('d/m/Y à H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Pour "Toutes les recettes" - catalogue -->
                                        <div class="index-card-meta index-card-meta-creation">
                                            <div class="index-card-meta-item">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                                                </svg>
                                                Par {{ $recette->user->name }}
                                            </div>
                                            
                                            @if($recette->updated_at != $recette->created_at)
                                                <!-- Si modifiée, afficher la date de modification avec point gris -->
                                                <div class="index-card-meta-item">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="3"/>
                                                    </svg>
                                                    Modifiée le {{ $recette->updated_at->format('d/m/Y à H:i') }}
                                                </div>
                                            @else
                                                <!-- Si pas modifiée, afficher la date de création avec calendrier -->
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
                        @endforeach
                    </div>
                @else
                    <div class="index-empty">
                        <div class="index-empty-icon">🍽️</div>
                        <h3>Aucune recette trouvée</h3>
                        <p>Commencez par créer votre première recette !</p>
                        <a href="{{ route('recettes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block mt-4">
                            ➕ Créer ma première recette
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
