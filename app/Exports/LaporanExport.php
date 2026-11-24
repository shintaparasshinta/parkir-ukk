<?php

namespace App\Exports; // Menentukan namespace export

use App\Models\Transaksi; // Mengimpor model Transaksi untuk query data
use Maatwebsite\Excel\Concerns\FromCollection; // Interface: menyediakan data dari collection
use Maatwebsite\Excel\Concerns\WithHeadings; // Interface: menambahkan baris heading
use Maatwebsite\Excel\Concerns\WithMapping; // Interface: transformasi tiap baris
use Maatwebsite\Excel\Concerns\WithStyles; // Interface: styling worksheet
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Interface: auto-size kolom
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Kelas Worksheet untuk styling
use PhpOffice\PhpSpreadsheet\Style\Fill; // Konstanta & helper untuk fill (warna latar)
use PhpOffice\PhpSpreadsheet\Style\Border; // Konstanta & helper untuk border
use PhpOffice\PhpSpreadsheet\Style\Alignment; // Konstanta & helper untuk alignment
use Illuminate\Support\Enumerable; // Interface untuk tipe return collection (wajib untuk deklarasi return type)

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize // Mendefinisikan class export dengan 5 interface
{
    protected $startDate; // Menyimpan filter tanggal mulai
    protected $endDate; // Menyimpan filter tanggal akhir
    protected $areaId; // Menyimpan filter area parkir
    protected $jenisKendaraan; // Menyimpan filter jenis kendaraan
    protected $rowNumber = 0; // Counter nomor baris (untuk kolom NO)

    public function __construct($startDate, $endDate, $areaId, $jenisKendaraan) // Konstruktor untuk menerima filter dari controller
    {
        $this->startDate = $startDate; // Simpan filter tanggal mulai
        $this->endDate = $endDate; // Simpan filter tanggal akhir
        $this->areaId = $areaId; // Simpan filter area
        $this->jenisKendaraan = $jenisKendaraan; // Simpan filter jenis kendaraan
    }

    public function collection(): Enumerable // Method menyediakan data yang akan diekspor
    {
        $query = Transaksi::with(['kendaraan', 'areaParkir', 'petugas']); // Query dasar dengan eager load relasi

        if ($this->startDate && $this->endDate) { // Jika filter tanggal diisi
            $query->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']); // Filter transaksi dalam rentang tanggal
        }
        if ($this->areaId) { // Jika filter area dipilih
            $query->where('area_id', $this->areaId); // Filter berdasarkan area
        }
        if ($this->jenisKendaraan) { // Jika filter jenis kendaraan dipilih
            $query->whereHas('kendaraan', function($q) { // Filter relasi kendaraan
                $q->where('jenis_kendaraan', $this->jenisKendaraan); // Kondisi jenis kendaraan sesuai filter
            });
        }

        return $query->orderBy('created_at', 'desc')->get(); // Urutkan terbaru, kembalikan collection
    }

    public function headings(): array // Method menyediakan baris heading di Excel
    {
        return [ // Array judul kolom
            'NO', // Nomor urut
            'KODE TRANSAKSI', // Kode transaksi
            'PLAT NOMOR', // Plat nomor kendaraan
            'PEMILIK', // Nama pemilik
            'JENIS KENDARAAN', // Motor/Mobil
            'AREA PARKIR', // Nama area
            'PETUGAS', // Nama petugas
            'WAKTU MASUK', // Timestamp masuk
            'WAKTU KELUAR', // Timestamp keluar
            'DURASI (HARI)', // Lama parkir dalam hari
            'TOTAL TARIF', // Total biaya
            'STATUS', // Status transaksi
        ];
    }

    public function map($item): array // Method transformasi satu baris data transaksi
    {
        $this->rowNumber++; // Increment nomor baris

        $durasi = '-'; // Default durasi jika belum keluar
        if ($item->waktu_masuk && $item->waktu_keluar) { // Jika waktu masuk dan keluar keduanya ada
            $masuk = \Carbon\Carbon::parse($item->waktu_masuk)->startOfDay(); // Normalisasi waktu masuk ke awal hari
            $keluar = \Carbon\Carbon::parse($item->waktu_keluar)->startOfDay(); // Normalisasi waktu keluar ke awal hari
            $durasi = $masuk->diffInDays($keluar) + 1; // Hitung selisih hari + 1 (hari masuk dihitung)
        }

        return [ // Array nilai per kolom
            $this->rowNumber, // NO: nomor urut
            $item->kode_transaksi, // Kode transaksi
            $item->kendaraan->plat_nomor ?? '-', // Plat nomor, fallback '-' jika relasi kosong
            $item->kendaraan->pemilik ?? '-', // Pemilik, fallback '-'
            $item->kendaraan->jenis_kendaraan ?? '-', // Jenis kendaraan, fallback '-'
            $item->areaParkir->nama_area ?? '-', // Nama area, fallback '-'
            $item->petugas->nama ?? '-', // Nama petugas, fallback '-'
            $item->waktu_masuk ? \Carbon\Carbon::parse($item->waktu_masuk)->format('d/m/Y H:i') : '-', // Format waktu masuk atau '-'
            $item->waktu_keluar ? \Carbon\Carbon::parse($item->waktu_keluar)->format('d/m/Y H:i') : '-', // Format waktu keluar atau '-'
            $durasi, // Durasi hari (angka atau '-')
            'Rp ' . number_format($item->tarif, 0, ',', '.'), // Total tarif terformat rupiah
            strtoupper($item->status), // Status uppercase (MASUK/KELUAR)
        ];
    }

    public function styles(Worksheet $sheet): array // Method styling worksheet
    {
        // Styling Header
        $sheet->getStyle('A1:L1')->applyFromArray([ // Ambil style untuk baris pertama (heading) kolom A-L
            'font' => [ // Pengaturan font
                'bold' => true, // Teks tebal
                'color' => ['rgb' => 'FFFFFF'], // Warna teks putih
                'size' => 12, // Ukuran font 12
            ],
            'fill' => [ // Pengaturan latar
                'fillType' => Fill::FILL_SOLID, // Isi solid
                'startColor' => ['rgb' => '172554'], // Warna biru gelap
            ],
            'alignment' => [ // Pengaturan perataan
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Rata tengah horizontal
                'vertical' => Alignment::VERTICAL_CENTER, // Rata tengah vertikal
            ],
            'borders' => [ // Pengaturan border
                'allBorders' => [ // Border semua sisi
                    'borderStyle' => Border::BORDER_THIN, // Garis tipis
                    'color' => ['rgb' => '000000'], // Warna hitam
                ],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25); // Atur tinggi baris heading jadi 25

        // Styling Body
        $lastRow = $sheet->getHighestRow(); // Dapatkan nomor baris terakhir yang berisi data
        $sheet->getStyle('A2:L' . $lastRow)->applyFromArray([ // Style untuk seluruh body (baris 2 hingga terakhir)
            'borders' => [ // Pengaturan border
                'allBorders' => [ // Border semua sisi
                    'borderStyle' => Border::BORDER_THIN, // Garis tipis
                    'color' => ['rgb' => 'AAAAAA'], // Warna abu-abu
                ],
            ],
            'alignment' => [ // Pengaturan perataan
                'vertical' => Alignment::VERTICAL_CENTER, // Rata tengah vertikal
            ],
        ]);

        // Zebra Stripes
        for ($i = 2; $i <= $lastRow; $i++) { // Loop tiap baris data
            if ($i % 2 == 0) { // Jika baris genap
                $sheet->getStyle('A' . $i . ':L' . $i)->applyFromArray([ // Style untuk baris tersebut
                    'fill' => [ // Pengaturan latar
                        'fillType' => Fill::FILL_SOLID, // Isi solid
                        'startColor' => ['rgb' => 'F3F4F6'], // Warna abu-abu muda (zebra)
                    ],
                ]);
            }
        }

        return []; // Tidak perlu override style default, kembalikan array kosong
    }
}