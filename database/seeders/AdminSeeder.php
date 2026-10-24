<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => env('ADMIN_EMAIL', 'admin@cap2.test')], ['name' => 'CAP Admin', 'role' => 'admin', 'password' => env('ADMIN_PASSWORD', 'ChangeMe_Admin_123!')]);
        User::updateOrCreate(['email' => env('EDITOR_EMAIL', 'editor@cap2.test')], ['name' => 'CAP Editor', 'role' => 'editor', 'password' => env('EDITOR_PASSWORD', 'ChangeMe_Editor_123!')]);
    }
}
