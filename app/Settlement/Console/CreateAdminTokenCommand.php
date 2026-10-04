<?php

declare(strict_types=1);

namespace App\Settlement\Console;

use App\Models\User;
use Illuminate\Console\Command;

final class CreateAdminTokenCommand extends Command
{
    protected $signature = 'settlement:admin-token {email : Super admin email} {--hours=8 : Token lifetime in hours} {--force : Allow in production}';

    protected $description = 'Create a short-lived API token for the settlement admin console';

    public function handle(): int
    {
        if (app()->environment('production') && ! (bool) $this->option('force')) {
            $this->error('Refusing to create a token in production without --force.');

            return self::FAILURE;
        }

        $user = User::query()
            ->where('email', (string) $this->argument('email'))
            ->where('is_super_admin', true)
            ->first();

        if ($user === null) {
            $this->error('No super admin found with that email.');

            return self::FAILURE;
        }

        $hours = max(1, (int) $this->option('hours'));
        $token = $user->createToken('settlement-admin-console', ['*'], now()->addHours($hours));

        $this->line($token->plainTextToken);

        return self::SUCCESS;
    }
}
