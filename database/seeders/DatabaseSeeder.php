<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create some users with usernames and roles
        \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@purespiceherbs.com',
            'role' => 'super-admin',
        ]);

        \App\Models\User::factory()->count(5)->create();

        // Create default team group
        $this->call(TeamGroupSeeder::class);

        // sample project
        // $user = \App\Models\User::first();
        // \App\Models\Project::create([
        //     'user_id' => $user->id,
        //     'project_url' => 'https://example.com',
        // ]);
    }
}
