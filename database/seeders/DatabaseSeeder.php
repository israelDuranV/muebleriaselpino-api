<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call(CatalogosSeeder::class);
        $this->call(MueblesSeeder::class);
        $this->call(DireccionSeeder::class);
        $this->call(PermissionsAndRolesSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(MuebleriasSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(PedidosSeeder::class);
        $this->call(MuebleFotoSeeder::class);
    }
}
