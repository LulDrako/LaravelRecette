<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Bienvenue sur LaravelCook ! 👨‍🍳</h3>
                    <p class="mb-4">Vous êtes connecté(e) avec succès. Voici ce que vous pouvez faire :</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                            <h4 class="font-semibold text-orange-800 mb-2">📋 Mes Recettes</h4>
                            <p class="text-sm text-gray-600 mb-3">Gérez vos recettes personnelles</p>
                            <a href="{{ route('mes-recettes.index') }}" class="bg-orange-500 text-white px-4 py-2 rounded text-sm hover:bg-orange-600">
                                Voir mes recettes
                            </a>
                        </div>
                        
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-blue-800 mb-2">🍽️ Catalogue</h4>
                            <p class="text-sm text-gray-600 mb-3">Découvrez toutes les recettes</p>
                            <a href="{{ route('recettes.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                                Explorer le catalogue
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
