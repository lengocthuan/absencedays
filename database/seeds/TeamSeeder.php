<?php

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            [
                'name' => 'PHP',
                'description' => 'Team PHP'
            ],
            [
                'name' => 'JAVA',
                'description' => 'Team JAVA'
            ],
            [
                'name' => 'BA',
                'description' => 'Team BA'
            ],
            [
                'name' => 'Solution',
                'description' => 'Team Solution'
            ],
            [
                'name' => '.NET',
                'description' => 'Team .NET'
            ],
            [
                'name' => 'MOBILE',
                'description' => 'Team MOBILE'
            ],
            [
                'name' => 'DESIGN',
                'description' => 'Team DESIGN'
            ],
            [
                'name' => 'QM',
                'description' => 'Team Quality Management'
            ],
            [
                'name' => 'FE',
                'description' => 'Team Front-end'
            ],
            [
                'name' => 'AI',
                'description' => 'Team AI'
            ],
            [
                'name' => 'PM',
                'description' => 'Team PM'
            ],
            [
                'name' => 'Administrative Management',
                'description' => 'Team HC-QT'
            ]
        ];
        
        foreach ($teams as $key => $value) {
            Team::create($value);
        }
    }
}
