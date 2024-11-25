<?php

namespace App\Exports;

use App\Models\Cuti;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SakitExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    protected $mulai;
    protected $akhir;
    protected $user;
    protected $status; // Tambahkan user

    public function __construct($mulai, $akhir, $status, $user )
    {
        $this->mulai = $mulai;
        $this->akhir = $akhir;
        $this->user = $user;
        $this->status = $status;  // Inisialisasi user
    }

    public function collection()
    {
        // Ambil data izin sakit dengan filter tanggal dan jabatan
        $query = Cuti::where('nama_cuti', 'Izin Sakit');

        if ($this->mulai && $this->akhir) {
            $query->whereBetween('tanggal', [$this->mulai, $this->akhir]);
        }

        if ($this->status) {
            $query->where('status_cuti', $this->status);
        }

        // Filter berdasarkan jabatan jika user adalah admin_divisi
        if ($this->user->is_admin === 'admin_divisi') {
            $query->whereHas('karyawan', function ($query) {
                $query->where('jabatan_id', $this->user->jabatan_id);
            });
        }

        return $query->with('Karyawan')->get()->map(function ($cuti) {
            return [
                'nama_pegawai' => $cuti->Karyawan->name,
                'nama_cuti' => $cuti->nama_cuti,
                'tanggal' => $cuti->tanggal,
                'alasan_cuti' => $cuti->alasan_cuti,
                'jam_awal' => $cuti->jam_awal,
                'jam_akhir' => $cuti->jam_akhir,
                'status_cuti' => $cuti->status_cuti,
                'catatan' => $cuti->catatan,
                'tanggal_approve' => $cuti->tanggal_approve,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pegawai',
            'Nama Cuti',
            'Tanggal',
            'Alasan Cuti',
            'Jam Awal',
            'Jam Akhir',
            'Status Cuti',
            'Catatan',
            'Tanggal Approve',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => 'yyyy-mm-dd',
            'E' => 'hh:mm:ss',
            'F' => 'hh:mm:ss',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling header (baris 1)
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal('center');

        // Menambahkan border pada seluruh tabel
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle("A1:$highestColumn$highestRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}
