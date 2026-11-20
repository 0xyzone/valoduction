<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Login;
use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Support\Enums\Width;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel('admin');
    }

    public function test_login_page_authenticates_using_username(): void
    {
        $user = User::factory()->create([
            'username' => 'jdoe',
            'password' => Hash::make('secret-pass'),
            'is_active' => true,
        ]);

        Livewire::test(Login::class)
            ->fillForm([
                'username' => 'jdoe',
                'password' => 'secret-pass',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_authenticate_into_panel(): void
    {
        User::factory()->inactive()->create([
            'username' => 'inactive_user',
            'password' => Hash::make('secret-pass'),
        ]);

        Livewire::test(Login::class)
            ->fillForm([
                'username' => 'inactive_user',
                'password' => 'secret-pass',
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['username']);

        $this->assertGuest();
    }

    public function test_make_filament_user_command_uses_username_without_requiring_email(): void
    {
        $this->artisan('make:filament-user', [
            '--name' => 'Admin User',
            '--username' => 'adminuser',
            '--primary-phone' => '+15551234567',
            '--email' => '',
            '--password' => 'password123',
            '--panel' => 'admin',
        ])->assertSuccessful();

        $user = User::query()->where('username', 'adminuser')->first();

        $this->assertNotNull($user);
        $this->assertSame('Admin User', $user->name);
        $this->assertSame('+15551234567', $user->primary_phone);
        $this->assertNull($user->email);
        $this->assertTrue($user->is_active);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_global_create_edit_and_view_actions_use_7xl_slideovers(): void
    {
        $createAction = CreateAction::make();
        $editAction = EditAction::make();
        $viewAction = ViewAction::make();

        $this->assertTrue($createAction->isModalSlideOver());
        $this->assertSame(Width::SevenExtraLarge, $createAction->getModalWidth());

        $this->assertTrue($editAction->isModalSlideOver());
        $this->assertSame(Width::SevenExtraLarge, $editAction->getModalWidth());

        $this->assertTrue($viewAction->isModalSlideOver());
        $this->assertSame(Width::SevenExtraLarge, $viewAction->getModalWidth());
    }

    public function test_user_resource_can_list_create_and_edit_users_in_slideover_modals(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        Livewire::test(ManageUsers::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$admin])
            ->callAction(CreateAction::class, data: [
                'name' => 'Jane Doe',
                'username' => 'janedoe',
                'primary_phone' => '+15559876543',
                'email' => null,
                'password' => 'password123',
                'is_active' => true,
            ])
            ->assertHasNoActionErrors();

        $created = User::query()->where('username', 'janedoe')->first();
        $this->assertNotNull($created);
        $this->assertSame('Jane Doe', $created->name);
        $this->assertSame('+15559876543', $created->primary_phone);

        Livewire::test(ManageUsers::class)
            ->callTableAction(EditAction::class, $created, data: [
                'name' => 'Jane Updated',
                'is_active' => false,
            ])
            ->assertHasNoTableActionErrors();

        $created->refresh();
        $this->assertSame('Jane Updated', $created->name);
        $this->assertFalse($created->is_active);
    }
}
