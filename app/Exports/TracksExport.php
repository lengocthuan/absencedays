<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class TracksExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $data;
    public function __construct($time)
    {
        $this->data = $time;
    }

    public function collection()
    {
        $newData = $this->data;
        for ($i = 0; $i < count($newData); $i++) {
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
                $event->sheet->getDelegate()->getStyle('F2:F999')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('G2:G999')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A2:Z999')->getFont()->setSize(14)->setName('Times New Roman');
            },
        ];
    }
}
