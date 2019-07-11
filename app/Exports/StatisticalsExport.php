<?php

namespace App\Exports;

use App\Models\Track;
use Maatwebsite\Excel\Concerns\FromCollection;

class StatisticalsExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        // $getInformation = Track::select('year', 'user_id', 'annual_leave_total', 'annua_leave_unused', 'January', 'February',
        $getInformation = Track::select()->get();
        $getInformation->makeHidden('id');
        // dd($getInformation[0]['user_id']);
        foreach ($getInformation as $value) {
            $show = ['user' => $value->getInfoUser->name];
            $mergeShow = implode($show);
            $value->user_id = $mergeShow;
            
        }
        dd($getInformation);
    }

    public function headings(): array
    {
        return [
            'Mã thứ tự',
            'Năm hiện tại',
            'Địa chỉ email',
            'Đội/Khối',
            'Vị trí',
            'Thời gian nghỉ phép',
            'Buổi',
            'Tổng ngày nghỉ',
        ];
    }
}
