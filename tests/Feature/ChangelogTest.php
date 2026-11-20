<?php

namespace Tests\Feature;

use App\Support\Changelog;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangelogTest extends TestCase
{
    use RefreshDatabase;

    public function test_changelog_parses_latest_version_and_releases(): void
    {
        $releases = Changelog::releases();

        $this->assertNotEmpty($releases);
        $this->assertSame('v1.0.0', Changelog::latestVersion());
        $this->assertTrue($releases[0]['is_latest']);
    }

    public function test_public_changelog_page_displays_timeline(): void
    {
        $response = $this->get(route('changelog'));

        $response->assertOk()
            ->assertSee('Changelog')
            ->assertSee(Changelog::latestVersion())
            ->assertSee('Latest');
    }

    public function test_filament_login_page_displays_brand_and_version_pill(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk()
            ->assertSee(config('app.name', 'Valoduction'))
            ->assertSee(Changelog::latestVersion())
            ->assertSee(route('changelog'));
    }

    public function test_filament_panel_logo_component_displays_brand_and_version_pill(): void
    {
        Filament::setCurrentPanel('admin');

        $view = $this->blade('<x-filament-panels::logo />');

        $view->assertSee(config('app.name', 'Valoduction'))
            ->assertSee(Changelog::latestVersion())
            ->assertSee(route('changelog'));
    }
}
