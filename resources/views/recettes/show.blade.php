@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <div style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
        

        <div style="padding: 20px;">
            <a href="{{ route('recettes.index') }}" 
               style="display: inline-flex; 
                      align-items: center; 
                      gap: 8px;
                      background: rgba(255, 255, 255, 0.9);
                      backdrop-filter: blur(10px);
                      color: #374151;
                      padding: 12px 20px;
                      border-radius: 12px;
                      text-decoration: none;
                      font-weight: 600;
                      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                      transition: all 0.3s ease;"
               onmouseover="this.style.background='white'; this.style.transform='translateY(-1px)'"
               onmouseout="this.style.background='rgba(255, 255, 255, 0.9)'; this.style.transform='translateY(0)'">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Retour aux recettes
            </a>
        </div>


        <div style="max-width: 1000px; margin: 0 auto; padding: 0 20px 40px 20px;">
            
    
            <div style="background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1); margin-bottom: 30px;">
                
                @if($recette->image)
                    <div style="height: 400px; background-image: url('{{ Storage::url($recette->image) }}'); background-size: cover; background-position: center; position: relative;">
                
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0, 0, 0, 0.7)); padding: 40px;">
                            <h1 style="color: white; font-size: 36px; font-weight: 700; margin: 0; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);">
                                {{ $recette->titre }}
                            </h1>
                            @if($recette->description)
                                <p style="color: rgba(255, 255, 255, 0.9); font-size: 18px; margin: 12px 0 0 0; text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);">
                                    {{ $recette->description }}
                                </p>
                            @endif
                        </div>
                    </div>
                @else
                    <div style="padding: 40px; text-align: center; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);">
                        <h1 style="color: #1f2937; font-size: 36px; font-weight: 700; margin: 0 0 12px 0;">
                            {{ $recette->titre }}
                        </h1>
                        @if($recette->description)
                            <p style="color: #6b7280; font-size: 18px; margin: 0;">
                                {{ $recette->description }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

    
            <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px; align-items: start;">
                
        
                <div style="display: flex; flex-direction: column; gap: 30px;">
                    
            
                    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);">
                        <h2 style="font-size: 24px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0; display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: #1f2937; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                                    <path d="M19 7h-3V6a4 4 0 0 0-8 0v1H5a1 1 0 0 0-1 1v11a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V8a1 1 0 0 0-1-1zM10 6a2 2 0 0 1 4 0v1h-4V6zm8 13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V9h2v1a1 1 0 0 0 2 0V9h4v1a1 1 0 0 0 2 0V9h2v10z"/>
                                </svg>
                            </div>
                            Ingrédients
                        </h2>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @php
                                $ingredients = explode("\n", $recette->ingredients);
                            @endphp
                            @foreach($ingredients as $ingredient)
                                @if(trim($ingredient) !== '')
                                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #f8fafc; border-radius: 12px; border-left: 4px solid #1f2937;">
                                        <span style="color: #374151; font-size: 16px;">{{ trim($ingredient) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

            
                    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);">
                        <h2 style="font-size: 24px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0; display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: #1f2937; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </div>
                            Instructions
                        </h2>
                        <div style="color: #374151; line-height: 1.7; font-size: 16px;">
                            {!! nl2br(e($recette->instructions)) !!}
                        </div>
                    </div>
                </div>

        
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    
            
                    <div style="background: white; border-radius: 20px; padding: 24px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0 0 16px 0;">
                            Informations
                        </h3>
                        
                        @if($recette->type)
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                                <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <svg width="16" height="16" fill="#6b7280" viewBox="0 0 24 24">
                                        <path d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="color: #6b7280; font-size: 12px; font-weight: 500; text-transform: uppercase;">Type</div>
                                    <div style="color: #1f2937; font-weight: 600;">{{ ucfirst($recette->type) }}</div>
                                </div>
                            </div>
                        @endif

                        @if($recette->temps_preparation)
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                                <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <svg width="16" height="16" fill="#6b7280" viewBox="0 0 24 24">
                                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6V12L16,14L15,15.5L11,13V6H12Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="color: #6b7280; font-size: 12px; font-weight: 500; text-transform: uppercase;">Temps</div>
                                    <div style="color: #1f2937; font-weight: 600;">{{ $recette->temps_preparation }} min</div>
                                </div>
                            </div>
                        @endif

                        @if($recette->portions)
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                                <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <svg width="16" height="16" fill="#6b7280" viewBox="0 0 24 24">
                                        <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="color: #6b7280; font-size: 12px; font-weight: 500; text-transform: uppercase;">Portions</div>
                                    <div style="color: #1f2937; font-weight: 600;">{{ $recette->portions }} pers.</div>
                                </div>
                            </div>
                        @endif

                
                        <div style="display: flex; align-items: center; gap: 12px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                            <div style="width: 32px; height: 32px; background: #1f2937; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                                </svg>
                            </div>
                            <div>
                                <div style="color: #6b7280; font-size: 12px; font-weight: 500; text-transform: uppercase;">Créé par</div>
                                <div style="color: #1f2937; font-weight: 600;">{{ $recette->user->name }}</div>
                            </div>
                        </div>
                    </div>

            
                    @if($recette->tags && count($recette->tags) > 0)
                        <div style="background: white; border-radius: 20px; padding: 24px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);">
                            <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0 0 16px 0;">
                                Tags
                            </h3>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @foreach($recette->tags as $tag)
                                    @php
                                        $tagLabels = [
                                            'sans-gluten' => 'Sans gluten',
                                            'vegetarien' => 'Végétarien',
                                            'vegan' => 'Vegan',
                                            'sans-lactose' => 'Sans lactose'
                                        ];
                                    @endphp
                                    <span style="background: #f1f5f9; 
                                                 color: #1f2937; 
                                                 padding: 6px 12px; 
                                                 border-radius: 16px; 
                                                 font-size: 13px; 
                                                 font-weight: 500;
                                                 border: 1px solid #e2e8f0;">
                                        {{ $tagLabels[$tag] ?? ucfirst($tag) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

            
                    @if($recette->user_id === auth()->id())
                        <div style="background: white; border-radius: 20px; padding: 24px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);">
                            <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0 0 16px 0;">
                                Actions
                            </h3>
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                <a href="{{ route('recettes.edit', $recette) }}" 
                                   style="background: #1f2937; 
                                          color: white; 
                                          padding: 12px 20px; 
                                          border-radius: 12px; 
                                          text-decoration: none; 
                                          font-weight: 600;
                                          text-align: center;
                                          transition: all 0.3s ease;"
                                   onmouseover="this.style.background='#374151'"
                                   onmouseout="this.style.background='#1f2937'">
                                    Modifier
                                </a>
                                <form action="{{ route('recettes.destroy', $recette) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="background: #ef4444; 
                                                   color: white; 
                                                   border: none; 
                                                   padding: 12px 20px; 
                                                   border-radius: 12px; 
                                                   font-weight: 600;
                                                   cursor: pointer;
                                                   width: 100%;
                                                   transition: all 0.3s ease;"
                                            onmouseover="this.style.background='#dc2626'"
                                            onmouseout="this.style.background='#ef4444'">
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
