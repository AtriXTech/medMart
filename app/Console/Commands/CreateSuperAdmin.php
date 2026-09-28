<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateSuperAdmin extends Command
{
    protected $signature = 'medmart:create-super-admin';
    protected $description = 'Create a MedMart super admin account';
    public function handle(): int
    {
        $name = $this->ask('Super Admin name');
        $email = $this->ask('Super Admin email');
        $password = $this->secret('Super Admin password');
        if (User::where('email', $email)->exists()) {
            $this->error('A user with this email already exists.');
            return self::FAILURE;
        }
        User::create(['pharmacy_id' => null, 'name' => $name, 'email' => $email, 'password' => $password, 'role' => null, 'status' => 'active', 'is_super_admin' => true,]);
        $this->info('Super Admin created successfully!');
        return self::SUCCESS;
    }
}
