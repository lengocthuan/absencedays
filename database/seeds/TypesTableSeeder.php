<?php

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name' => 'annual leave',
                'requirement' => 'bit.ly/annual_leave'
            ],
            [
                'name' => 'sick leave',
                'requirement' => 'bit.ly/sick_leave_01'
            ],
            [
                'name' => 'marriage leave',
                'requirement' => 'bit.ly/marriage_leave'
            ],
            [
                'name' => 'maternity leave',
                'requirement' => 'bit.ly/maternity_leave_01'
            ],
            [
                'name' => 'bereavement leave',
                'requirement' => 'bit.ly/bereavement_leave'
            ],
            [
                'name' => 'short term unpaid leave',
                'requirement' => 'bit.ly/short_term_unpaid_leave'
            ],
            [
                'name' => 'long term unpaid leave',
                'requirement' => 'bit.ly/long_term_unpaid_leave'
            ],
        ];
        
        foreach ($types as $key => $value) {
            Type::create($value);
        }
    }
}
