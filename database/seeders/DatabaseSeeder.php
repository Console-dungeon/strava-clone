<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $nadia = User::firstOrCreate(
            ['email' => 'nadia@doe.com'],
            [
                'name' => 'Nadia',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $nadia->activities()->createMany(
            Activity::factory()->count(50)->make()->toArray()
        );

        $users = User::factory()->count(100)->create();

        $allUsers = $users->push($nadia);

        Activity::factory()->count(1000)->make()->each(function ($activity) use ($allUsers) {
            $activity->user_id = $allUsers->random()->id;
            $activity->save();
        });
    }
}
