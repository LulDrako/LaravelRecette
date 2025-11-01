<?php

namespace Tests\Unit;

use App\Models\Recette;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecetteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que la relation avec l'utilisateur fonctionne
     */
    public function test_recette_belongs_to_user(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $recette = Recette::create([
            'titre' => 'Test Recette',
            'description' => 'Description test',
            'ingredients' => '- Ingrédient 1',
            'instructions' => '1. Instruction 1',
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $recette->user);
        $this->assertEquals('John Doe', $recette->user->name);
        $this->assertEquals($user->id, $recette->user_id);
    }

    /**
     * Test que les tags sont castés en array
     */
    public function test_tags_are_casted_to_array(): void
    {
        $user = User::factory()->create();

        $recette = Recette::create([
            'titre' => 'Recette avec tags',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $user->id,
            'tags' => ['sans-gluten', 'vegetarien', 'bio'],
        ]);

        $this->assertIsArray($recette->tags);
        $this->assertCount(3, $recette->tags);
        $this->assertContains('sans-gluten', $recette->tags);
        $this->assertContains('vegetarien', $recette->tags);
        $this->assertContains('bio', $recette->tags);
    }

    /**
     * Test que les champs fillable fonctionnent
     */
    public function test_fillable_attributes(): void
    {
        $user = User::factory()->create();

        $data = [
            'titre' => 'Recette fillable',
            'description' => 'Description fillable',
            'ingredients' => '- Ingrédient fillable',
            'instructions' => '1. Instruction fillable',
            'type' => 'plat',
            'temps_preparation' => 30,
            'portions' => 4,
            'user_id' => $user->id,
            'tags' => ['tag1', 'tag2'],
        ];

        $recette = Recette::create($data);

        $this->assertEquals('Recette fillable', $recette->titre);
        $this->assertEquals('Description fillable', $recette->description);
        $this->assertEquals('plat', $recette->type);
        $this->assertEquals(30, $recette->temps_preparation);
        $this->assertEquals(4, $recette->portions);
        $this->assertIsArray($recette->tags);
    }

    /**
     * Test de l'accessor getImageUrlAttribute
     */
    public function test_image_url_accessor_returns_image(): void
    {
        $user = User::factory()->create();

        $recette = Recette::create([
            'titre' => 'Recette avec image',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $user->id,
            'image' => 'data:image/jpeg;base64,test123',
        ]);

        $this->assertEquals('data:image/jpeg;base64,test123', $recette->image_url);
    }

    /**
     * Test qu'une recette peut être créée sans image
     */
    public function test_recette_can_be_created_without_image(): void
    {
        $user = User::factory()->create();

        $recette = Recette::create([
            'titre' => 'Recette sans image',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $user->id,
        ]);

        $this->assertNull($recette->image);
        $this->assertNotNull($recette->id);
    }

    /**
     * Test qu'une recette peut être créée sans tags
     */
    public function test_recette_can_be_created_without_tags(): void
    {
        $user = User::factory()->create();

        $recette = Recette::create([
            'titre' => 'Recette sans tags',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $user->id,
        ]);

        $this->assertNull($recette->tags);
    }

    /**
     * Test qu'une recette peut être créée avec type, temps et portions
     */
    public function test_recette_optional_fields(): void
    {
        $user = User::factory()->create();

        $recette = Recette::create([
            'titre' => 'Recette complète',
            'description' => 'Description complète',
            'ingredients' => '- Ingrédient 1\n- Ingrédient 2',
            'instructions' => '1. Étape 1\n2. Étape 2',
            'user_id' => $user->id,
            'type' => 'dessert',
            'temps_preparation' => 45,
            'portions' => 6,
        ]);

        $this->assertEquals('dessert', $recette->type);
        $this->assertEquals(45, $recette->temps_preparation);
        $this->assertEquals(6, $recette->portions);
    }

    /**
     * Test que les timestamps sont automatiquement gérés
     */
    public function test_timestamps_are_automatically_managed(): void
    {
        $user = User::factory()->create();

        $recette = Recette::create([
            'titre' => 'Test timestamps',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $user->id,
        ]);

        $this->assertNotNull($recette->created_at);
        $this->assertNotNull($recette->updated_at);
        
        // Modifier la recette
        sleep(1);
        $recette->update(['titre' => 'Titre modifié']);
        
        $this->assertTrue($recette->updated_at > $recette->created_at);
    }

    /**
     * Test que les tags peuvent être mis à jour
     */
    public function test_tags_can_be_updated(): void
    {
        $user = User::factory()->create();

        $recette = Recette::create([
            'titre' => 'Recette',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $user->id,
            'tags' => ['tag1'],
        ]);

        $this->assertCount(1, $recette->tags);

        $recette->update(['tags' => ['tag1', 'tag2', 'tag3']]);
        $recette->refresh();

        $this->assertCount(3, $recette->tags);
        $this->assertContains('tag2', $recette->tags);
        $this->assertContains('tag3', $recette->tags);
    }

    /**
     * Test qu'une recette peut être supprimée
     */
    public function test_recette_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $recette = Recette::create([
            'titre' => 'Recette à supprimer',
            'description' => 'Description',
            'ingredients' => '- Ingrédients',
            'instructions' => '1. Instructions',
            'user_id' => $user->id,
        ]);

        $recetteId = $recette->id;
        $recette->delete();

        $this->assertNull(Recette::find($recetteId));
    }
}

