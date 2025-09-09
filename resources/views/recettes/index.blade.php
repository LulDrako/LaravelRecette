<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes Recettes') }}
            </h2>
            <a href="{{ route('recettes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                ➕ Nouvelle Recette
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Messages de succès -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Liste des recettes -->
            @if($recettes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recettes as $recette)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <!-- Image de la recette -->
                            @if($recette->image)
                                <img src="{{ Storage::url($recette->image) }}" alt="{{ $recette->titre }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500 text-4xl">🍽️</span>
                                </div>
                            @endif
                            
                            <!-- Contenu -->
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $recette->titre }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($recette->description, 100) }}</p>
                                
                                <!-- Infos -->
                                <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                                    <span>👤 {{ $recette->user->name }}</span>
                                    @if($recette->temps_preparation)
                                        <span>⏱️ {{ $recette->temps_preparation }} min</span>
                                    @endif
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('recettes.show', $recette) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                        👁️ Voir
                                    </a>
                                    @if($recette->user_id === auth()->id())
                                        <a href="{{ route('recettes.edit', $recette) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-sm">
                                            ✏️ Modifier
                                        </a>
                                        <form action="{{ route('recettes.destroy', $recette) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                🗑️ Supprimer
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
