@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes Recettes') }}
            </h2>
            <a href="{{ route('recettes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Nouvelle Recette</span>
            </a>
        </div>
    </x-slot>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .aspect-square {
            aspect-ratio: 1 / 1;
        }
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Messages de succès -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- BOUTON NOUVELLE RECETTE - DESIGN ÉPURÉ -->
            <div style="text-align: center; margin: 40px 0 50px 0;">
                <a href="{{ route('recettes.create') }}" 
                   style="background: #1f2937; 
                          color: white; 
                          padding: 16px 32px; 
                          border-radius: 12px; 
                          text-decoration: none; 
                          font-weight: 600; 
                          font-size: 16px; 
                          box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                          transition: all 0.3s ease;
                          display: inline-flex;
                          align-items: center;
                          gap: 8px;
                          border: 1px solid #374151;"
                   onmouseover="this.style.background='#374151'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.15)'"
                   onmouseout="this.style.background='#1f2937'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.1)'">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                    </svg>
                    Créer une nouvelle recette
                </a>
            </div>

            <!-- Liste des recettes - DESIGN MODERNE -->
            @if($recettes->count() > 0)
                <div style="display: grid; 
                           grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); 
                           gap: 30px; 
                           max-width: 1200px; 
                           margin: 0 auto; 
                           padding: 0 20px;">
                    @foreach($recettes as $recette)
                        <div style="background: white; 
                                   border-radius: 20px; 
                                   overflow: hidden; 
                                   box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
                                   transition: all 0.3s ease;
                                   border: 1px solid rgba(255, 255, 255, 0.2);"
                             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0, 0, 0, 0.15)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 30px rgba(0, 0, 0, 0.12)'">
                            
                            <!-- Image Section -->
                            <div style="width: 100%; height: 240px; position: relative; overflow: hidden;">
                                @if($recette->image)
                                    <img src="{{ Storage::url($recette->image) }}" 
                                         alt="{{ $recette->titre }}" 
                                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                         onmouseover="this.style.transform='scale(1.05)'"
                                         onmouseout="this.style.transform='scale(1)'">
                                @else
                                    <div style="width: 100%; 
                                               height: 100%; 
                                               background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
                                               display: flex; 
                                               align-items: center; 
                                               justify-content: center;">
                                        <svg width="48" height="48" fill="#9ca3af" viewBox="0 0 24 24">
                                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Badge de type -->
                                @if($recette->type)
                                    <div style="position: absolute; 
                                               top: 16px; 
                                               right: 16px; 
                                               background: rgba(255, 255, 255, 0.9); 
                                               backdrop-filter: blur(10px);
                                               padding: 6px 12px; 
                                               border-radius: 20px; 
                                               font-size: 12px; 
                                               font-weight: 600; 
                                               color: #374151;">
                                        {{ ucfirst($recette->type) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Content Section -->
                            <div style="padding: 24px;">
                                <h3 style="font-size: 20px; 
                                           font-weight: 700; 
                                           margin: 0 0 12px 0; 
                                           color: #1f2937; 
                                           line-height: 1.3;">{{ $recette->titre }}</h3>
                                
                                <div style="display: flex; 
                                           gap: 16px; 
                                           margin-bottom: 20px; 
                                           font-size: 14px; 
                                           color: #6b7280;">
                                    @if($recette->temps_preparation)
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6V12L16,14L15,15.5L11,13V6H12Z"/>
                                            </svg>
                                            {{ $recette->temps_preparation }} min
                                        </div>
                                    @endif
                                    @if($recette->portions)
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                                            </svg>
                                            {{ $recette->portions }} pers.
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Tags -->
                                @if($recette->tags && count($recette->tags) > 0)
                                    <div style="display: flex; 
                                               flex-wrap: wrap; 
                                               gap: 6px; 
                                               margin-bottom: 20px;">
                                        @foreach($recette->tags as $tag)
                                            @php
                                                $tagLabels = [
                                                    'sans-gluten' => 'Sans gluten',
                                                    'vegetarien' => 'Végétarien',
                                                    'vegan' => 'Vegan',
                                                    'sans-lactose' => 'Sans lactose'
                                                ];
                                            @endphp
                                            <span style="background: #f3f4f6; 
                                                         color: #374151; 
                                                         padding: 4px 8px; 
                                                         border-radius: 12px; 
                                                         font-size: 12px; 
                                                         font-weight: 500;">
                                                {{ $tagLabels[$tag] ?? ucfirst($tag) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <!-- Actions -->
                                <div style="display: flex; gap: 12px;">
                                    <a href="{{ route('recettes.show', $recette) }}" 
                                       style="flex: 1;
                                              background: #1f2937;
                                              color: white;
                                              padding: 12px 20px;
                                              border-radius: 8px;
                                              text-decoration: none;
                                              font-weight: 600;
                                              text-align: center;
                                              font-size: 14px;
                                              transition: all 0.3s ease;
                                              border: 1px solid #374151;"
                                       onmouseover="this.style.background='#374151'; this.style.transform='translateY(-1px)'"
                                       onmouseout="this.style.background='#1f2937'; this.style.transform='translateY(0)'">
                                        Voir la recette
                                    </a>
                                    
                                    @if($recette->user_id === auth()->id())
                                        <form action="{{ route('recettes.destroy', $recette) }}" method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?');" 
                                              style="flex: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    style="background: #fee2e2;
                                                           color: #dc2626;
                                                           border: none;
                                                           padding: 12px;
                                                           border-radius: 12px;
                                                           cursor: pointer;
                                                           transition: all 0.3s ease;
                                                           width: 48px;
                                                           height: 48px;
                                                           display: flex;
                                                           align-items: center;
                                                           justify-content: center;"
                                                    onmouseover="this.style.background='#fecaca'"
                                                    onmouseout="this.style.background='#fee2e2'">
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
                <!-- Aucune recette -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-gray-500">
                        <span class="text-6xl">🍽️</span>
                        <h3 class="text-xl font-semibold mt-4">Aucune recette trouvée</h3>
                        <p class="mt-2">Commencez par créer votre première recette !</p>
                        <a href="{{ route('recettes.create') }}" class="inline-block mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            ➕ Créer ma première recette
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
