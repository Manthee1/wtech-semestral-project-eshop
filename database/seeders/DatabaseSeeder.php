<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Admin
        $admin = new \App\Models\User();
        $admin->id = 0;
        $admin->email = 'admin@rideout.com';
        $admin->password = '$2y$12$OjgXnlldeWRUmjzIMBgeeuRwXc/MJEkIvbKlnj8U2b18dyzzULwNK';
        $admin->role = 'Admin';
        $admin->first_name = 'Admin';
        $admin->last_name = 'rideout';

        $admin->save();
    }
}
