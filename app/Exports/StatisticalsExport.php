<?php

namespace App\Exports;

use App\Models\Track;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StatisticalsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $getInformation = Track::select()->get();
        $getInformation->makeHidden('id');
        foreach ($getInformation as $value) {
            $user_id = User::where('id', $value->user_id)->select()->get();
            $value->user_id = $user_id[0]->name;
            $arrayEmail = $user_id[0]->email;
            $arrayTeam = $user_id[0]->getTeam->name;
            $arrayPosition = $user_id[0]->getPosition->name;
            $arrayMarriage = 0.0;
            $arrayMaternity = 0.0;
            $arrayBereavement = 0.0;
            $arrayUnpaid = 0.0;

            if (is_null($value->annual_leave_unused)) {
                $value->annual_leave_unused = 0.0;
                $value->January = 0.0;
                $value->February = 0.0;
                $value->March = 0.0;
                $value->April = 0.0;
                $value->May = 0.0;
                $value->June = 0.0;
                $value->July = 0.0;
                $value->August = 0.0;
                $value->September = 0.0;
                $value->October = 0.0;
                $value->November = 0.0;
                $value->December = 0.0;
            } else {
                if ($value->January == null) {
                    $value->January = 0.0;
                }
                if ($value->February == null) {
                    $value->February = 0.0;
                }
                if ($value->March == null) {
                    $value->March = 0.0;
                }
                if ($value->April == null) {
                    $value->April = 0.0;
                }
                if ($value->May == null) {
                    $value->May = 0.0;
                }
                if ($value->June == null) {
                    $value->June = 0.0;
                }
                if ($value->July == null) {
                    $value->July = 0.0;
                }
                if ($value->August == null) {
                    $value->August = 0.0;
                }
                if ($value->September == null) {
                    $value->September = 0.0;
                }
                if ($value->October == null) {
                    $value->October = 0.0;
                }
                if ($value->November == null) {
                    $value->November = 0.0;
                }
                if ($value->December == null) {
                    $value->December = 0.0;
                }
            }
            $merge[] = $value->year . '+' . $value->user_id . '+' . $arrayEmail . '+' . $arrayTeam . '+' . $arrayPosition . '+' . $value->annual_leave_total . '+' . $value->annual_leave_unused . '+' . $value->January . '+' . $value->February . '+' . $value->March . '+' . $value->April . '+' . $value->May . '+' . $value->June . '+' . $value->July . '+' . $value->August . '+' . $value->September . '+' . $value->October . '+' . $value->November . '+' . $value->December . '+' . $arrayMarriage . '+' . $arrayMaternity . '+' . $arrayBereavement . '+' . $arrayUnpaid;

        }

        for ($i = 0; $i < count($merge); $i++) {
            $cut = explode('+', $merge[$i]);
            $newcut[] = $cut;
        }

        return (collect($newcut));
    }

    public function headings(): array
    {
        return [
            'Năm hiện tại',
            'Địa chỉ email',
            'Đội/Khối',
            'Vị trí',
            'Tổng phép năm',
            'Phép năm chưa dùng',
            'Tháng 1',
            'Tháng 2',
            'Tháng 3',
            'Tháng 4',
            'Tháng 5',
            'Tháng 6',
            'Tháng 7',
            'Tháng 8',
            'Tháng 9',
            'Tháng 10',
            'Tháng 11',
            'Tháng 12',
            'Phép ốm',
            'Kết hôn',
            'Khám thai',
            'Ma chay',
            'Nghỉ không lương',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ],
                    ],
                ];
                $styleAlignment = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    ],
                ];
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->applyFromArray($styleAlignment);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(18)->setName('Arial')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('A2:Z999')->getFont()->setSize(14)->setName('Times New Roman');
            },
        ];
    }
}
