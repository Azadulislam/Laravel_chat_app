<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if team group already exists
        $group = Group::firstOrCreate(
            ['name' => 'Team'],
            [
                'description' => 'Default team group for all users',
                'created_by' => User::first()?->id ?? 1,
            ]
        );

        // Create or get the conversation for this group
        $conversation = Conversation::firstOrCreate(
            ['group_id' => $group->id],
            ['type' => 'group']
        );

        // Add all existing users to the group and conversation
        $users = User::all();
        foreach ($users as $user) {
            // Add to group members
            $group->members()->syncWithoutDetaching([$user->id => ['role' => 'member']]);
            
            // Add to conversation participants
            $conversation->participants()->syncWithoutDetaching([$user->id]);
        }
    }
}
