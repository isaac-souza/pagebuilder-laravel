<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Image;
use App\Models\Account;

class ImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_authenticated_users_can_upload_files()
    {
        // Arrange
        Auth::logout();
        
        // Pre assert
        $this->assertDatabaseCount('images', 0);
        
        // Act
        $file = UploadedFile::fake()->image('ebook-cover.jpg', 400, 400);
        $response = $this->postJson('/api/v1/images', [
            'file' => $file,
        ]);
        
        // Assert
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertDatabaseCount('images', 0);
    }

    public function test_an_uploaded_image_is_stored_in_the_correct_folder_and_a_thumb_is_generated()
    {
        // Arrange
        Storage::fake('public');

        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Account::factory()->for($user)->create();
        
        // Pre assert
        $this->assertDatabaseCount('images', 0);
        
        // Act
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('ebook-cover.jpg', 400, 400);
        $response = $this->postJson(route('images.store'), [
            'file' => $file,
        ]);
        
        // Assert
        $image = Image::first();

        $response->assertStatus(Response::HTTP_CREATED);
        Storage::disk('public')->assertExists($image->path);
        Storage::disk('public')->assertExists($image->thumb_path);
        $this->assertDatabaseCount('images', 1);
    }

    public function test_when_an_image_is_deleted_its_corresponding_files_are_also_deleted()
    {
        // Arrange
        Storage::fake('public');

        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Account::factory()->for($user)->create();

        $this->actingAs($user);

        $file = UploadedFile::fake()->image('ebook-cover.jpg', 400, 400);

        $this->postJson(route('images.store'), [
            'file' => $file,
        ]);

        $image = Image::first();
        
        // Pre assert
        $this->assertDatabaseCount('images', 1);
        
        // Act
        $response = $this->delete(route('images.delete', $image->uuid));
        
        // Assert
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        Storage::disk('public')->assertMissing($image->path);
        Storage::disk('public')->assertMissing($image->thumb_path);
        $this->assertDatabaseCount('images', 0);
    }

}
