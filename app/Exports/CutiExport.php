<?php

namespace App\Exports;

use App\Models\Cuti;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CutiExport implements FromCollection, WithColumnFormatting, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $dataCuti;

    public function __construct($dataCuti)
    {
        $this->dataCuti = $dataCuti; // Menerima data cuti
    }

    public function collection()
    {
        return $this->dataCuti; // Mengembalikan data cuti
    }

    public function map($cuti): array
    {
        return [
            $cuti->Karyawan->name,
            $cuti->nama_cuti,
            $cuti->tanggal . ' sd ' . $cuti->tanggal_akhir,
            $cuti->alasan_cuti,
            $cuti->status_cuti,
            $cuti->catatan,
            $cuti->tanggal_approve ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Pegawai',
            'Nama Cuti',
            'Tanggal',
            'Alasan Cuti',
            'Status Cuti',
            'Catatan',
            'Tanggal Approve',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        // Add borders to all cells
        $sheet->getStyle("A1:$highestColumn" . $highestRow)
              ->getBorders()
              ->getAllBorders()
              ->setBorderStyle(Border::BORDER_THIN);

        // Bold headers
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        // Center the headers
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal('center');
    }

    public function columnFormats(): array
    {
        return [
            // Misalkan kolom ke-3 (tanggal) ingin Anda format sebagai 'dd-mm-yyyy'
            'C' => 'dd-mm-yyyy',
            // Tambahkan format lain jika diperlukan
        ];
    }
}
