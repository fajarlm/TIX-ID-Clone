<?php

namespace App\Exports;

use App\Models\Schedule;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduleExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $no = 0;
    public function collection()
    {
        return Schedule::with(['cinema', 'movie'])->get();
    }
    public function headings(): array
    {
        return [
            'No',
            'Flim',
            'Cinema',
            'harga',
            'Jam Tayang',
        ];  
    }

    public function map($schedule): array
    {
        $this->no++;
        return [
            $this->no,
            $schedule->movie->title,
            $schedule->cinema->name,
            'Rp'.number_format($schedule->price, 2, ',', '.'),
            array_merge($schedule->hours)
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // ambil row & col terakhir
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();

        // bikin range otomatis dari A1 sampe data terakhir
        $cellRange = "A1:{$lastCol}{$lastRow}";

        // apply border ke semua cell
        $sheet->getStyle($cellRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // (opsional) heading dibikin bold
        $sheet->getStyle('A1:' . $lastCol . '1')->getFont()->setBold(true);

        return [];
    }
}
