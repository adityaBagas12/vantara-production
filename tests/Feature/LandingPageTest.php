<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_landing_page_settings(): void
    {
        $this->get(route('admin.landing-page.edit'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_replace_landing_page_images(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->put(route('admin.landing-page.update'), [
            'hero_image' => UploadedFile::fake()->image('hero.jpg', 1600, 900),
            'about_image' => UploadedFile::fake()->image('about.webp', 1200, 800),
        ]);

        $response->assertRedirect(route('admin.landing-page.edit'));

        $siteSetting = SiteSetting::query()->firstOrFail();
        $this->assertNotNull($siteSetting->hero_image_path);
        $this->assertNotNull($siteSetting->about_image_path);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $siteSetting->hero_image_path));
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $siteSetting->about_image_path));
    }

    public function test_homepage_displays_configured_landing_page_images(): void
    {
        SiteSetting::factory()->create([
            'hero_image_path' => '/storage/landing-page/hero.jpg',
            'about_image_path' => '/storage/landing-page/about.webp',
        ]);

        $this->get(route('home'))
            ->assertSee('/storage/landing-page/hero.jpg')
            ->assertSee('/storage/landing-page/about.webp');
    }

    public function test_admin_can_delete_a_landing_page_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('landing-page/hero.jpg', 'image');
        $admin = User::factory()->create(['is_admin' => true]);
        $siteSetting = SiteSetting::factory()->create([
            'hero_image_path' => '/storage/landing-page/hero.jpg',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.landing-page.destroy', 'hero'));

        $response->assertRedirect(route('admin.landing-page.edit'));
        $this->assertNull($siteSetting->fresh()->hero_image_path);
        Storage::disk('public')->assertMissing('landing-page/hero.jpg');
    }
}
