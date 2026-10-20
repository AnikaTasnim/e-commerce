<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('store.admin_email');
        $password = config('store.admin_password');

        if (! $email || ! $password) {
            return;
        }

        User::updateOrCreate(['email' => $email], [
            'name' => config('store.admin_name'),
            'password' => $password,
            'is_admin' => true,
        ]);
    }
}
