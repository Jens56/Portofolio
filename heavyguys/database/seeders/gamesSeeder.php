<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class gamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('games')->insert([
            [
                'name' => 'Zombie Survival',
                'description' => 'Het is een game waar je doel is om zo lang mogelijk te overleven tegen zombies. Je kunt verschillende wapens en upgrades gebruiken om de zombies te verslaan.',
                'image_name' => 'zombiesurvival.png',
            ],
            [
                'name' => 'Zombie Survival',
                'description' => 'Het is een game waar je doel is om zo lang mogelijk te overleven tegen zombies. Je kunt verschillende wapens en upgrades gebruiken om de zombies te verslaan.',
                'image_name' => 'zombiesurvival.png',
            ],
            [
                'name' => 'Zombie Survival',
                'description' => 'Het is een game waar je doel is om zo lang mogelijk te overleven tegen zombies. Je kunt verschillende wapens en upgrades gebruiken om de zombies te verslaan.',
                'image_name' => 'zombiesurvival.png',
            ],
            [
                'name' => 'Zombie Survival',
                'description' => 'Het is een game waar je doel is om zo lang mogelijk te overleven tegen zombies. Je kunt verschillende wapens en upgrades gebruiken om de zombies te verslaan.',
                'image_name' => 'zombiesurvival.png',
            ],
            [
                'name' => 'Zombie Survival',
                'description' => 'Het is een game waar je doel is om zo lang mogelijk te overleven tegen zombies. Je kunt verschillende wapens en upgrades gebruiken om de zombies te verslaan.',
                'image_name' => 'zombiesurvival.png',
            ]
        ]);
    }
}
