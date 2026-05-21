<?php

namespace App\Exports;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class AbsensiExport implements WithMultipleSheets
{
    public function __construct(protected array $filters = []) {}

    public function sheets(): array
    {
        return [
            new AbsensiRekapSheet($this->filters),
            new AbsensiDetailSheet($this->filters),
        ];
    }
}

// ---- Sheet 1: Rekap Per Jemaat ----

class AbsensiRekapSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(protected array $filters = []) {}

    public function title(): string { return 'Rekap Per Jemaat'; }

    public function collection()
    {
        $bulan = $this->filters['bulan'] ?? now()->format('Y-m');

        return Absensi::with('jemaat')
            ->where('approval_status', 'approved')
            ->whereYear('tanggal', Carbon::parse($bulan)->year)
            ->whereMonth('tanggal', Carbon::parse($bulan)->month)
            ->when($this->filters['jemaat_id'] ?? null, fn($q, $id) => $q->where('jemaat_id', $id))
            ->select('jemaat_id',
                DB::raw("SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
                DB::raw("SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as total_izin"),
                DB::raw("SUM(CASE WHEN status = 'tidak_hadir' THEN 1 ELSE 0 END) as total_tidak_hadir"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy('jemaat_id')
            ->with('jemaat')
            ->get()
            ->map(function ($item) {
                $item->persentase = $item->total > 0
                    ? round(($item->total_hadir / $item->total) * 100) : 0;
                return $item;
            })
            ->sortByDesc('persentase');
    }

    public function headings(): array
    {
        return ['No', 'Nama Jemaat', 'Total Hadir', 'Total Izin', 'Total Tidak Hadir', 'Total Ibadah', 'Persentase Hadir (%)'];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->jemaat?->nama_lengkap ?? '-',
            $row->total_hadir,
            $row->total_izin,
            $row->total_tidak_hadir,
            $row->total,
            $row->persentase . '%',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A5F']],
            ],
        ];
    }
}

// ---- Sheet 2: Detail Absensi ----

class AbsensiDetailSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(protected array $filters = []) {}

    public function title(): string { return 'Detail Absensi'; }

    public function collection()
    {
        $bulan = $this->filters['bulan'] ?? now()->format('Y-m');

        return Absensi::with(['jemaat', 'schedule'])
            ->where('approval_status', 'approved')
            ->whereYear('tanggal', Carbon::parse($bulan)->year)
            ->whereMonth('tanggal', Carbon::parse($bulan)->month)
            ->when($this->filters['jemaat_id'] ?? null, fn($q, $id) => $q->where('jemaat_id', $id))
            ->when($this->filters['schedule_id'] ?? null, fn($q, $id) => $q->where('schedule_id', $id))
            ->orderBy('tanggal')
            ->orderBy('jemaat_id')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Nama Jemaat', 'Jadwal Ibadah', 'Status', 'Keterangan', 'Metode Input'];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->tanggal->format('d/m/Y'),
            $row->jemaat?->nama_lengkap ?? '-',
            $row->schedule?->title ?? $row->jenis_ibadah,
            ucfirst(str_replace('_', ' ', $row->status)),
            $row->keterangan ?? $row->alasan_izin ?? '-',
            match($row->input_method) {
                'qr'    => 'QR Code',
                'admin' => 'Admin',
                default => 'Mandiri',
            },
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD4AF37']],
            ],
        ];
    }
}
