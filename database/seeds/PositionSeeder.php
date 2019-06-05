<?php

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = [
            [
                'name' => 'Offcial Staff',
                'description' => 'official staff'
            ],
            [
                'name' => 'Fresher',
                'description' => 'Fresher'
            ],
            [
                'name' => 'Internship',
                'description' => 'Internship'
            ],
            [
                'name' => 'Temporary Employees',
                'description' => 'TE'
            ]
        ];

        foreach ($positions as $key => $value) {
            Position::create($value);
        }
    }
}
