<?php

namespace Tests\Feature;

use App\Models\Recette;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecetteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que la page d'accueil affiche les recettes
     */
    public function test_index_page_displays_recipes(): void
    {
        $user = User::factory()->create();
        
        Recette::create([
            'titre' => 'Tarte aux pommes',
            'description' => 'Une délicieuse tarte',
            'ingredients' => '- Pommes\n- Pâte',
            'instructions' => '1. Étaler la pâte\n2. Ajouter les pommes',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/recettes');

        $response->assertStatus(200);
        $response->assertSee('Tarte aux pommes');
    }

    /**
     * Test que seul un utilisateur authentifié peut créer une recette
     */
    public function test_guest_cannot_create_recipe(): void
    {
        $response = $this->get('/recettes/create');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test qu'un utilisateur authentifié peut accéder au formulaire de création
     */
    public function test_authenticated_user_can_access_create_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/recettes/create');

        $response->assertStatus(200);
    }

    /**
     * Test de création d'une recette avec des données valides
     */
    public function test_user_can_create_recipe_with_valid_data(): void
    {
        $user = User::factory()->create();

        $recetteData = [
            'titre' => 'Poulet rôti',
            'description' => 'Un délicieux poulet',
            'ingredients' => '- 1 poulet\n- Sel\n- Poivre',
            'instructions' => '1. Assaisonner le poulet\n2. Enfourner 1h',
            'type' => 'plat',
            'temps_preparation' => 60,
            'portions' => 4,
            'tags' => ['sans-gluten'],
        ];

        $response = $this->actingAs($user)->post('/recettes', $recetteData);

        $response->assertRedirect('/recettes');
        $this->assertDatabaseHas('recettes', [
            'titre' => 'Poulet rôti',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test de validation - titre requis
     */
    public function test_recipe_creation_requires_title(): void
    {
        $user = User::factory()->create();

        $recetteData = [
            'description' => 'Une description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
        ];

        $response = $this->actingAs($user)->post('/recettes', $recetteData);

        $response->assertSessionHasErrors(['titre']);
    }

    /**
     * Test de validation - description requise
     */
    public function test_recipe_creation_requires_description(): void
    {
        $user = User::factory()->create();

        $recetteData = [
            'titre' => 'Un titre',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
        ];

        $response = $this->actingAs($user)->post('/recettes', $recetteData);

        $response->assertSessionHasErrors(['description']);
    }

    /**
     * Test qu'un utilisateur peut voir les détails d'une recette
     */
    public function test_user_can_view_recipe_details(): void
    {
        $user = User::factory()->create();
        
        $recette = Recette::create([
            'titre' => 'Salade César',
            'description' => 'Une salade classique',
            'ingredients' => '- Laitue\n- Poulet\n- Parmesan',
            'instructions' => '1. Mélanger tous les ingrédients',
            'user_id' => $user->id,
        ]);

        $response = $this->get("/recettes/{$recette->id}");

        $response->assertStatus(200);
        $response->assertSee('Salade César');
        $response->assertSee('Laitue');
    }

    /**
     * Test que seul le propriétaire peut modifier sa recette
     */
    public function test_only_owner_can_edit_recipe(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $recette = Recette::create([
            'titre' => 'Ma recette',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $owner->id,
        ]);

        // L'autre utilisateur ne peut pas accéder à l'édition
        $response = $this->actingAs($otherUser)->get("/recettes/{$recette->id}/edit");
        $response->assertStatus(403);

        // Le propriétaire peut accéder à l'édition
        $response = $this->actingAs($owner)->get("/recettes/{$recette->id}/edit");
        $response->assertStatus(200);
    }

    /**
     * Test qu'un utilisateur peut modifier sa propre recette
     */
    public function test_user_can_update_own_recipe(): void
    {
        $user = User::factory()->create();
        
        $recette = Recette::create([
            'titre' => 'Ancienne recette',
            'description' => 'Ancienne description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $user->id,
        ]);

        $updateData = [
            'titre' => 'Nouvelle recette',
            'description' => 'Nouvelle description',
            'ingredients' => '- Nouveaux ingrédients',
            'instructions' => '1. Nouvelles instructions',
        ];

        $response = $this->actingAs($user)->put("/recettes/{$recette->id}", $updateData);

        $response->assertRedirect("/recettes/{$recette->id}");
        $this->assertDatabaseHas('recettes', [
            'id' => $recette->id,
            'titre' => 'Nouvelle recette',
        ]);
    }

    /**
     * Test que seul le propriétaire peut supprimer sa recette
     */
    public function test_only_owner_can_delete_recipe(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $recette = Recette::create([
            'titre' => 'Ma recette',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $owner->id,
        ]);

        // L'autre utilisateur ne peut pas supprimer
        $response = $this->actingAs($otherUser)->delete("/recettes/{$recette->id}");
        $response->assertStatus(403);
        $this->assertDatabaseHas('recettes', ['id' => $recette->id]);

        // Le propriétaire peut supprimer
        $response = $this->actingAs($owner)->delete("/recettes/{$recette->id}");
        $response->assertRedirect('/recettes');
        $this->assertDatabaseMissing('recettes', ['id' => $recette->id]);
    }

    /**
     * Test du filtre de recherche
     */
    public function test_search_filter_works(): void
    {
        $user = User::factory()->create();
        
        Recette::create([
            'titre' => 'Tarte aux pommes',
            'description' => 'Une tarte sucrée',
            'ingredients' => '- Pommes',
            'instructions' => '1. Cuire',
            'user_id' => $user->id,
        ]);

        Recette::create([
            'titre' => 'Poulet grillé',
            'description' => 'Un plat salé',
            'ingredients' => '- Poulet',
            'instructions' => '1. Griller',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/recettes?search=pommes');

        $response->assertStatus(200);
        $response->assertSee('Tarte aux pommes');
        $response->assertDontSee('Poulet grillé');
    }

    /**
     * Test du filtre par type
     */
    public function test_type_filter_works(): void
    {
        $user = User::factory()->create();
        
        Recette::create([
            'titre' => 'Soupe',
            'description' => 'Entrée',
            'ingredients' => '- Légumes',
            'instructions' => '1. Cuire',
            'type' => 'entree',
            'user_id' => $user->id,
        ]);

        Recette::create([
            'titre' => 'Gâteau',
            'description' => 'Dessert',
            'ingredients' => '- Farine',
            'instructions' => '1. Cuire',
            'type' => 'dessert',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/recettes?type=dessert');

        $response->assertStatus(200);
        $response->assertSee('Gâteau');
        $response->assertDontSee('Soupe');
    }

    /**
     * Test de la page "Mes recettes" qui affiche seulement les recettes de l'utilisateur
     */
    public function test_mes_recettes_shows_only_user_recipes(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Recette::create([
            'titre' => 'Recette User 1',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $user1->id,
        ]);

        Recette::create([
            'titre' => 'Recette User 2',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($user1)->get('/mes-recettes');

        $response->assertStatus(200);
        $response->assertSee('Recette User 1');
        $response->assertDontSee('Recette User 2');
    }

    /**
     * Test des tags sur les recettes
     */
    public function test_recipe_can_have_tags(): void
    {
        $user = User::factory()->create();

        $recetteData = [
            'titre' => 'Recette sans gluten',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'tags' => ['sans-gluten', 'vegetarien'],
        ];

        $response = $this->actingAs($user)->post('/recettes', $recetteData);

        $response->assertRedirect('/recettes');
        
        $recette = Recette::where('titre', 'Recette sans gluten')->first();
        $this->assertNotNull($recette);
        $this->assertContains('sans-gluten', $recette->tags);
        $this->assertContains('vegetarien', $recette->tags);
    }
}

