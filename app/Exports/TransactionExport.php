<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithStyles, WithCustomStartCell, WithColumnFormatting
{
    protected $transactions;
    protected $date;
    protected $outlet;

    public function __construct($transactions, $date, $outlet)
    {
        $this->transactions = $transactions;
        $this->date = $date;
        $this->outlet = $outlet;
    }

    public function collection()
    {
        return collect($this->transactions);
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function map($transactions): array
    {
        // static $rowNumber = 0;

        // $rowNumber++;

        return [
            ' ',
            // $rowNumber,
            date('d-M-y, H:i:s', strtotime($transactions->created_at)),
            $transactions->transaction->customer->name,
            $transactions->transaction->customer->whatsapp_number,
            $transactions->transaction->outlet->outlet_name,
            $transactions->merk . ' ' . $transactions->size,
            $transactions->treatment_name,
            1,
            $transactions->cost,
            $transactions->partner_price == 0 ? '0' : $transactions->partner_price,
            $transactions->cost - $transactions->partner_price,
            $transactions->transaction->payment_method == 'cash' ? 'Cash' : 'QRIS',
            $transactions->transaction->payment_status == 'paid' ? 'Lunas' : 'Belum',
            ucwords(str_replace('_', ' ', $transactions->transaction->transaction_status)),
            $transactions->transaction->proof_of_handover ? config('app.url') . '/storage/' . $transactions->transaction->proof_of_handover : '',
        ];
    }

    public function headings(): array
    {
        return [
            ['  ', 'LAPORAN TRANSAKSI ALJU SHOES CLEAN', '', '', '', '', '', ''],
            ['  ', 'OUTLET: ' . $this->outlet],
            ['  ', 'BULAN: ' . str_replace('-', ' ', $this->date)],
            ['  ', '', '', '', '', '', '', ''],
            ['  ', 'TANGGAL', 'NAMA', 'NO HP', 'OUTLET', 'MERK', 'TREATMENT', 'QTY', 'HARGA TREATMENT / LABA KOTOR', 'POTONGAN MITRA', 'LABA SETELAH POTONGAN MITRA', 'METODE PEMBAYARAN', 'STATUS PEMBAYARAN', 'STATUS TRANSAKSI', 'BUKTI PENGAMBILAN'],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => '0',
            'I' => 'Rp#,##0.00',
            'J' => 'Rp#,##0.00',
            'K' => 'Rp#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $dataRows = $sheet->getHighestDataRow();

        $sheet->getStyle('B6:O' . $dataRows)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('B6:O6')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        // Menerapkan latar belakang warna pada heading
        $sheet->getStyle('B6:O6')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'C4D79B'],
            ],
        ]);
    }
}
