<?php

namespace App\Console\Commands;

use Filament\Commands\MakeUserCommand;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[AsCommand(name: 'make:filament-user', aliases: [
    'filament:make-user',
    'filament:user',
])]
class MakeFilamentUserCommand extends MakeUserCommand
{
    /**
     * @return array<InputOption>
     */
    protected function getOptions(): array
    {
        return [
            new InputOption(
                name: 'name',
                shortcut: null,
                mode: InputOption::VALUE_REQUIRED,
                description: 'The name of the user',
            ),
            new InputOption(
                name: 'username',
                shortcut: null,
                mode: InputOption::VALUE_REQUIRED,
                description: 'A unique username for the user',
            ),
            new InputOption(
                name: 'primary-phone',
                shortcut: null,
                mode: InputOption::VALUE_REQUIRED,
                description: 'The primary phone number of the user',
            ),
            new InputOption(
                name: 'email',
                shortcut: null,
                mode: InputOption::VALUE_REQUIRED,
                description: 'An optional email address for the user',
            ),
            new InputOption(
                name: 'password',
                shortcut: null,
                mode: InputOption::VALUE_REQUIRED,
                description: 'The password for the user (min. 8 characters)',
            ),
            new InputOption(
                name: 'panel',
                shortcut: null,
                mode: InputOption::VALUE_REQUIRED,
                description: 'The panel to create the user in',
            ),
        ];
    }

    /**
     * @return array{
     *     'name': string,
     *     'username': string,
     *     'primary_phone': ?string,
     *     'email': ?string,
     *     'password': string,
     *     'is_active': bool,
     * }
     */
    protected function getUserData(): array
    {
        $username = $this->options['username'] ?? text(
            label: 'Username',
            required: true,
            validate: fn (string $username): ?string => match (true) {
                static::getUserModel()::query()->where('username', $username)->exists() => 'A user with this username already exists.',
                default => null,
            },
        );

        $primaryPhone = $this->options['primary-phone'] ?? text(
            label: 'Primary phone (optional)',
            required: false,
        );

        $email = $this->options['email'] ?? text(
            label: 'Email address (optional)',
            required: false,
            validate: fn (string $email): ?string => match (true) {
                filled($email) && ! filter_var($email, FILTER_VALIDATE_EMAIL) => 'The email address must be valid.',
                filled($email) && static::getUserModel()::query()->where('email', $email)->exists() => 'A user with this email address already exists.',
                default => null,
            },
        );

        return [
            'name' => $this->options['name'] ?? text(
                label: 'Name',
                required: true,
            ),
            'username' => $username,
            'primary_phone' => filled($primaryPhone) ? $primaryPhone : null,
            'email' => filled($email) ? $email : null,
            'password' => Hash::make($this->options['password'] ?? password(
                label: 'Password',
                required: true,
            )),
            'is_active' => true,
        ];
    }
}
