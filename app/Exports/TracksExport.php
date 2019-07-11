<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TracksExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $data;
    public function __construct($time)
    {
        $this->data = $time;
    }

    // public function collection()
    // {
    //     return Track::all();
    // }

    public function collection()
    {
        $newData = $this->data;
        for ($i=0; $i < count($newData) ; $i++) {
            $timeDetails = implode(', ', $newData[$i]['time_details']);
            $newData[$i]['time_details'] = $timeDetails;
            $atTime = implode(', ', $newData[$i]['at_time']);
            $newData[$i]['at_time'] = $atTime;
        }
        return (collect($newData));
    }
    public function headings(): array
    {
        return [
            'Mã thứ tự',
            'Tên nhân viên',
            'Địa chỉ email',
            'Đội/Khối',
            'Vị trí',
            'Thời gian nghỉ phép',
            'Buổi',
            'Tổng ngày nghỉ',
        ];
    }
}
