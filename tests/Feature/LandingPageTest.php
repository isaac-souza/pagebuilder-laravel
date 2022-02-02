<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\LandingPage;
use App\Models\Account;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_landing_page_can_be_soft_deleted()
    {
        // Arrange
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $account = Account::factory()->for(($user))->create();
        $landingPage = LandingPage::factory()->for($account)->create();
        
        // Pre assert
        $this->assertEquals(1, LandingPage::count());
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseHas('landing_pages', [
            'uuid' => $landingPage->uuid,
            'deleted_at' => null,
        ]);
        
        // Act
        $this->actingAs($user);
        $response = $this->delete(route('landing-pages.destroy', $landingPage->uuid));
        
        // Assert
        $response->assertStatus(200);
        $this->assertEquals(0, LandingPage::count());
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseHas('landing_pages', [
            'uuid' => $landingPage->uuid,
            'deleted_at' => now(),
        ]);
    }

    public function test_only_an_authenticated_user_can_delete_a_landing_page()
    {
        // Arrange
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $account = Account::factory()->for(($user))->create();
        $landingPage = LandingPage::factory()->for($account)->create();
        
        // Pre assert
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseCount('landing_pages', 1);
        $this->assertDatabaseHas('landing_pages', [
            'uuid' => $landingPage->uuid,
            'deleted_at' => null,
        ]);
        
        // Act
        Auth::logout();
        $response = $this->deleteJson(route('landing-pages.destroy', $landingPage->uuid));
        
        // Assert
        $response->assertStatus(401);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseCount('landing_pages', 1);
        $this->assertDatabaseHas('landing_pages', [
            'uuid' => $landingPage->uuid,
            'deleted_at' => null,
        ]);
    }

    public function test_404_must_be_returned_if_a_landing_page_is_not_found_when_trying_to_delete()
    {
        // Arrange
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $account = Account::factory()->for(($user))->create();
        $landingPage = LandingPage::factory()->for($account)->create();
        
        // Pre assert
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseCount('landing_pages', 1);
        $this->assertDatabaseHas('landing_pages', [
            'uuid' => $landingPage->uuid,
            'deleted_at' => null,
        ]);
        
        // Act
        $this->actingAs($user);
        $response = $this->deleteJson(route('landing-pages.destroy', Str::uuid()));
        
        // Assert
        $response->assertStatus(404);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseCount('landing_pages', 1);
        $this->assertDatabaseHas('landing_pages', [
            'uuid' => $landingPage->uuid,
            'deleted_at' => null,
        ]);
    }

    public function test_a_user_cannot_delete_other_user_landing_page()
    {
        // Arrange
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Account::factory()->for(($user))->create();

        $otherUserLandingPage = LandingPage::factory()->create();
        
        // Pre assert
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('accounts', 2);
        $this->assertDatabaseCount('landing_pages', 1);
        $this->assertDatabaseHas('landing_pages', [
            'uuid' => $otherUserLandingPage->uuid,
            'deleted_at' => null,
        ]);
        
        // Act
        $this->actingAs($user);
        $response = $this->deleteJson(route('landing-pages.destroy', $otherUserLandingPage->uuid));
        
        // Assert
        $response->assertStatus(404);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('accounts', 2);
        $this->assertDatabaseCount('landing_pages', 1);
        $this->assertDatabaseHas('landing_pages', [
            'uuid' => $otherUserLandingPage->uuid,
            'deleted_at' => null,
        ]);
    }

    public function test_a_user_can_create_a_landing_page()
    {
        // Arrange
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Account::factory()->for(($user))->create();

        // Pre assert
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseCount('landing_pages', 0);
        
        // Act
        $this->actingAs($user);
        $response = $this->postJson(route('landing-pages.store'), [
            'name' => 'New landing pages name',
        ]);
        
        // Assert
        $response->assertStatus(201);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseCount('landing_pages', 1);
        $this->assertDatabaseHas('landing_pages', [
            'name' => 'New landing pages name',
            'deleted_at' => null,
        ]);
    }

    public function test_the_name_is_required()
    {
        // Arrange
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Account::factory()->for(($user))->create();

        // Pre assert
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseCount('landing_pages', 0);
        
        // Act
        $this->actingAs($user);
        $response = $this->postJson(route('landing-pages.store'), [
            'name' => null,
        ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonFragment([
            0 => 'The name field is required.',
            0 => 'The slug field is required.',
        ]);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseCount('landing_pages', 0);
    }

    public function test_only_authenticated_users_can_create_landing_pages()
    {
        // Arrange

        // Pre assert
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('accounts', 0);
        $this->assertDatabaseCount('landing_pages', 0);
        
        // Act
        Auth::logout();
        $response = $this->postJson(route('landing-pages.store'), [
            'name' => 'It doesn\'t matter',
        ]);

        // Assert
        $response->assertStatus(401);
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('accounts', 0);
        $this->assertDatabaseCount('landing_pages', 0);
    }

    public function test_guest_users_can_view_a_published_landingpage()
    {
        // Arrange
        $landingPage = LandingPage::factory()->create([
            'name' => 'Test Landing Page',
            'slug' => 'test-landing-page',
        ]);

        // Pre assert
        $this->assertDatabaseCount('landing_pages', 1);
        
        // Act
        Auth::logout();
        $response = $this->getJson(route('public.landing-pages.show', $landingPage->slug));

        // Assert
        $response->assertStatus(200);
    }

}
