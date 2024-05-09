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


        // Clear all files from the product images storage
        $files = glob(storage_path('public/products/*'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Clear all products, makes, models, drivetrains, engine_types,  body_types, and Product images
        \App\Models\Product::query()->delete();
        \App\Models\ProductMake::query()->delete();
        \App\Models\ProductModel::query()->delete();
        \App\Models\ProductDrivetrain::query()->delete();
        \App\Models\ProductEngineType::query()->delete();
        \App\Models\ProductBodyType::query()->delete();
        \App\Models\ProductImage::query()->delete();

        // Now run a python file that will seed the database with the necessary data
        $command = 'python3 ' . base_path('seed.py');
        exec($command);

        // Now execute products-seed.sql to seed the products table
        $sql = file_get_contents(base_path('products-seed.sql'));
        \Illuminate\Support\Facades\DB::unprepared($sql);
    }
}
