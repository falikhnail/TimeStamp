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

class IzinExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    protected $status;
    protected $nama_izin;
    protected $mulai;
    protected $akhir;

    public function __construct($status, $nama_izin, $mulai, $akhir)
    {
        $this->status = $status;
        $this->nama_izin = $nama_izin;
        $this->mulai = $mulai;
        $this->akhir = $akhir;
    }

    public function collection()
{
    // Memfilter data berdasarkan status, nama izin, dan tanggal
    $query = Cuti::with('Karyawan')->where('nama_cuti', 'like', 'Izin%')
        ->where('nama_cuti', '!=', 'Izin Sakit'); // Menambahkan pengecualian untuk 'Izin Sakit'

    if ($this->status) {
        $query->where('status_cuti', $this->status);
    }

    if ($this->nama_izin) {
        $query->where('nama_cuti', $this->nama_izin);
    }

    if ($this->mulai && $this->akhir) {
        $query->whereBetween('tanggal', [$this->mulai, $this->akhir]);
    }

    // Dapatkan user yang sedang login
    $user = auth()->user();

    // Jika user adalah admin divisi, hanya ambil data karyawan yang divisinya sama
    if ($user->is_admin === 'admin_divisi') {
        $query->whereHas('karyawan', function ($query) use ($user) {
            $query->where('jabatan_id', $user->jabatan_id);
        });
    }

    return $query->get()->map(function($cuti) {
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
            'Nama Izin',
            'Tanggal',
            'Alasan Izin',
            'Jam Awal',
            'Jam Akhir',
            'Status Izin',
            'Catatan',
            'Tanggal Approve',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => 'yyyy-mm-dd',  // Format kolom tanggal
            'E' => 'hh:mm:ss',    // Format kolom jam awal
            'F' => 'hh:mm:ss',    // Format kolom jam akhir
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
