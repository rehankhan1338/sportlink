<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create an admin user';

    public function handle()
    {
        try {
            // Create admin
            Admin::create([
                'username' => 'admin',
                'email' => 'admin@sportlink.com',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ]);

            $this->info('Admin user created successfully!');
            $this->info('Username: admin');
            $this->info('Password: admin123');
        } catch (\Exception $e) {
            $this->error('Error creating admin user: ' . $e->getMessage());
        }
    }
} 