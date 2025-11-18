<?php

namespace App\Exports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithMapping; 
use Maatwebsite\Excel\Concerns\WithStyles;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MovieExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $no = 0;
    public function collection()
    {
        return Movie::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Film',
            'Genre',
            'Sutradara',
            'Durasi (menit)',
            'Rating',
            'poster',
            'Sinopsis',
        ];
    }

    public function map($movie): array
    {
        return [
            ++$this->no,
            $movie->title,
            $movie->genre,
            $movie->director,
            Carbon::parse($movie->duration)->format('H').' jam '.Carbon::parse($movie->duration)->format('i').' menit',
            $movie->age_rating."+",
            asset('storage/posters/'.$movie->poster),
            $movie->description,
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
