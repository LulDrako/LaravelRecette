@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $recette->titre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Image de la recette -->
                    @if($recette->image)
                        <div class="mb-6 text-center">
                            <img src="{{ Storage::url($recette->image) }}" 
                                 alt="{{ $recette->titre }}" 
                                 class="max-w-md mx-auto h-64 object-cover rounded-2xl shadow-lg">
                        </div>
                    @endif

                    <!-- Informations de base -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-700 mb-2">Type</h3>
                            <p class="text-gray-600">{{ $recette->type ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-700 mb-2">Temps de préparation</h3>
                            <p class="text-gray-600">
                                {{ $recette->temps_preparation ? $recette->temps_preparation . ' minutes' : 'Non spécifié' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-700 mb-2">Portions</h3>
                            <p class="text-gray-600">
                                {{ $recette->portions ? $recette->portions . ' personnes' : 'Non spécifié' }}
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Description</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $recette->description }}</p>
                    </div>

                    <!-- Ingrédients -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Ingrédients</h3>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <pre class="whitespace-pre-wrap text-gray-700">{{ $recette->ingredients }}</pre>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Instructions</h3>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <pre class="whitespace-pre-wrap text-gray-700">{{ $recette->instructions }}</pre>
                        </div>
                    </div>

                    <!-- Informations auteur -->
                    <div class="border-t pt-4 mb-6">
                        <p class="text-sm text-gray-500">
                            Recette créée par <span class="font-medium">{{ $recette->user->name }}</span>
                            le {{ $recette->created_at->format('d/m/Y à H:i') }}
                        </p>
                        @if($recette->updated_at != $recette->created_at)
                            <p class="text-sm text-gray-500">
                                Dernière modification le {{ $recette->updated_at->format('d/m/Y à H:i') }}
                            </p>
                        @endif
                    </div>

                    <!-- Actions (seulement pour le propriétaire) -->
                    @if(auth()->id() === $recette->user_id)
                        <div class="flex flex-wrap gap-3 pt-4 border-t">
                            <a href="{{ route('recettes.edit', $recette) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                                ✏️ Modifier
                            </a>
                            <form action="{{ route('recettes.destroy', $recette) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Bouton retour -->
                    <div class="mt-6">
                        <a href="{{ route('recettes.index') }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            ← Retour à la liste des recettes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
