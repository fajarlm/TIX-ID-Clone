<?php

namespace App\Exports;

use App\Models\Promo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithMapping; 

class PromoExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $no = 0;
    public function collection()
    {
        return Promo::all();
    }

    public function headings():array {
        return [
            'No',
            'Kode Promo',
            'Diskon',
            'Tipe',
        ];
    }

    public function map($promo): array {
        return [
            ++$this->no,            
            $promo->promo_code,
            $promo->discount,
            $promo->type,
        ];
    }
}
