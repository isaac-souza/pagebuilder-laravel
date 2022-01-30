<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\LandingPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

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
        $account = Account::factory()->for(($user))->create();

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
}
