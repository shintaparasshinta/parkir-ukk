<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Enumerable; // <-- WAJIB TAMBAH INI

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $areaId;
    protected $jenisKendaraan;
    protected $rowNumber = 0;

    public function __construct($startDate, $endDate, $areaId, $jenisKendaraan)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->areaId = $areaId;
        $this->jenisKendaraan = $jenisKendaraan;
    }

    // TAMBAHKAN ": Enumerable" DI SINI
    public function collection(): Enumerable
    {
        $query = Transaksi::with(['kendaraan', 'areaParkir', 'petugas']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        }
        if ($this->areaId) {
            $query->where('area_id', $this->areaId);
        }
        if ($this->jenisKendaraan) {
            $query->whereHas('kendaraan', function($q) {
                $q->where('jenis_kendaraan', $this->jenisKendaraan);
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'KODE TRANSAKSI',
            'PLAT NOMOR',
            'PEMILIK',
            'JENIS KENDARAAN',
            'AREA PARKIR',
            'PETUGAS',
            'WAKTU MASUK',
            'WAKTU KELUAR',
            'DURASI (HARI)',
            'TOTAL TARIF',
            'STATUS',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;

        $durasi = '-';
        if ($item->waktu_masuk && $item->waktu_keluar) {
            $masuk = \Carbon\Carbon::parse($item->waktu_masuk)->startOfDay();
            $keluar = \Carbon\Carbon::parse($item->waktu_keluar)->startOfDay();
            $durasi = $masuk->diffInDays($keluar) + 1;
        }

        return [
            $this->rowNumber,
            $item->kode_transaksi,
            $item->kendaraan->plat_nomor ?? '-',
            $item->kendaraan->pemilik ?? '-',
            $item->kendaraan->jenis_kendaraan ?? '-',
            $item->areaParkir->nama_area ?? '-',
            $item->petugas->nama ?? '-',
            $item->waktu_masuk ? \Carbon\Carbon::parse($item->waktu_masuk)->format('d/m/Y H:i') : '-',
            $item->waktu_keluar ? \Carbon\Carbon::parse($item->waktu_keluar)->format('d/m/Y H:i') : '-',
            $durasi,
            'Rp ' . number_format($item->tarif, 0, ',', '.'),
            strtoupper($item->status),
        ];
    }

    // TAMBAHKAN ": array" DI SINI
    public function styles(Worksheet $sheet): array
    {
        // Styling Header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '172554'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);

        // Styling Body
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A2:L' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'AAAAAA'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Zebra Stripes
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':L' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'],
                    ],
                ]);
            }
        }

        return [];
    }
}