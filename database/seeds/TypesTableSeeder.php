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
                'name' => 'nghỉ phép (năm)',
                'requirement' => 'bit.ly/annual_leave'
            ],
            [
                'name' => 'nghỉ ốm',
                'requirement' => 'bit.ly/sick_leave_01'
            ],
            [
                'name' => 'nghỉ việc riêng (kết hôn)',
                'requirement' => 'bit.ly/marriage_leave'
            ],
            [
                'name' => 'nghỉ thai sản (khám thai)',
                'requirement' => 'bit.ly/maternity_leave_01'
            ],
            [
                'name' => 'nghỉ việc riêng (ma chay)',
                'requirement' => 'bit.ly/bereavement_leave'
            ],
            [
                'name' => 'nghỉ không lương ngắn hạn',
                'requirement' => 'bit.ly/short_term_unpaid_leave'
            ],
            [
                'name' => 'nghỉ không lương dài hạn',
                'requirement' => 'bit.ly/long_term_unpaid_leave'
            ],
        ];
        
        foreach ($types as $key => $value) {
            Type::create($value);
        }
    }
}
