<?php

namespace App\Exports;

use App\Models\Omset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OmsetExport implements FromCollection, WithHeadings, WithMapping
{
    protected $omsets;

    public function __construct($omsets)
    {
        $this->omsets = $omsets;
    }

    public function collection()
    {
        return $this->omsets; // Menggunakan data omset yang sudah difilter di controller
    }

    public function headings(): array
    {
        return [
            'ID', // Kolom pertama adalah ID
            'Tanggal',
            'Nama Klien',
            'Alamat',
            'Project',
            'Sumber Lead',
            'Nominal'
        ]; // Judul untuk setiap kolom yang akan muncul di Excel
    }

    public function map($omset): array
    {
        return [
            $omset->id_omset,
            $omset->tanggal,
            $omset->nama_klien,
            $omset->alamat,
            $omset->project,
            $omset->sumber_lead,
            'Rp ' . number_format($omset->nominal, 2, ',', '.') // Format nominal dengan mata uang
        ];
    }
}
