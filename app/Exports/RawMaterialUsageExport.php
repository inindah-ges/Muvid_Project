<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RawMaterialUsageExport implements FromCollection
{
    protected $report;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($report, $dateFrom, $dateTo)
    {
        $this->report = $report;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->report['details']);
    }

    public function headings(): array
    {
        return [
            // Judul Laporan
            ['RAW MATERIAL USAGE REPORT'],
            ['Period: ' . $this->dateFrom . ' - ' . $this->dateTo],
            ['Generated at: ' . now()->format('d M Y H:i:s')],
            [''],

            // Summary Header
            ['USAGE SUMMARY'],
            ['Material', 'Category', 'Total Usage', 'Average Usage', 'Usage Count'],

            // Data Summary diisi di withStyles
            [''],
            [''],

            // Detail Header
            ['USAGE DETAILS'],
            [
                'No',
                'Date',
                'Material',
                'Category',
                'Quantity Used',
                'Unit',
                'User'
            ],
        ];
    }

    public function map($usage): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $usage->date->format('d M Y'),
            $usage->rawMaterial->name,
            $usage->rawMaterial->category->name,
            $usage->quantity_used,
            $usage->rawMaterial->unit,
            $usage->user->name
        ];
    }

    public function title(): string
    {
        return 'Usage Report';
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk judul
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A1:G3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Style untuk header summary
        $sheet->mergeCells('A5:G5');
        $sheet->getStyle('A5')->getFont()->setBold(true);

        // Mengisi data summary
        $row = 6;
        foreach ($this->report['summary'] as $summary) {
            $sheet->setCellValue('A' . $row, $summary['material']);
            $sheet->setCellValue('B' . $row, $summary['category']);
            $sheet->setCellValue('C' . $row, $summary['total_usage'] . ' ' . $summary['unit']);
            $sheet->setCellValue('D' . $row, number_format($summary['average_usage'], 1) . ' ' . $summary['unit']);
            $sheet->setCellValue('E' . $row, $summary['usage_count'] . 'x');
            $row++;
        }

        // Style untuk header details
        $sheet->mergeCells('A9:G9');
        $sheet->getStyle('A9')->getFont()->setBold(true);

        // Style untuk header tabel
        $sheet->getStyle('A10:G10')->getFont()->setBold(true);
        $sheet->getStyle('A6:E6')->getFont()->setBold(true);

        // Border untuk tabel
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        // Apply border ke summary
        $sheet->getStyle('A6:E' . ($row-1))->applyFromArray($borderStyle);

        // Apply border ke details
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A10:G' . $lastRow)->applyFromArray($borderStyle);

        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            5 => ['font' => ['bold' => true]],
            9 => ['font' => ['bold' => true]],
            10 => ['font' => ['bold' => true]],
        ];
    }
}
