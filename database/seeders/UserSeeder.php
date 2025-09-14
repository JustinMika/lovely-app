<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('nom', 'Admin')->first();
        $gerantRole = Role::where('nom', 'Gérant')->first();
        $caissierRole = Role::where('nom', 'Caissier')->first();

        // Créer un utilisateur admin par défaut
        User::firstOrCreate(
            ['email' => 'admin@lovely-app.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Créer un gérant par défaut
        User::firstOrCreate(
            ['email' => 'gerant@lovely-app.com'],
            [
                'name' => 'Gérant Principal',
                'password' => Hash::make('password'),
                'role_id' => $gerantRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Créer un caissier par défaut
        User::firstOrCreate(
            ['email' => 'caissier@lovely-app.com'],
            [
                'name' => 'Caissier Principal',
                'password' => Hash::make('password'),
                'role_id' => $caissierRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
