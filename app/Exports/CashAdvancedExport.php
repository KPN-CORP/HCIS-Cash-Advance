<?php

namespace App\Exports;

use App\Models\CATransaction;
use App\Models\Employee;
use App\Models\ca_approval;
use App\Models\ca_sett_approval;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class CashAdvancedExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $fromDate;
    protected $untilDate;
    protected $stat;

    public function __construct($startDate, $endDate, $fromDate, $untilDate, $stat)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->fromDate = $fromDate;
        $this->untilDate = $untilDate;
        $this->stat = $stat;
    }

    public function collection()
    {
        $query = CATransaction::select(
            DB::raw("CASE
                WHEN ca_transactions.type_ca = 'dns' THEN 'Dinas'
                WHEN ca_transactions.type_ca = 'ndns' THEN 'Non Dinas'
                WHEN ca_transactions.type_ca = 'entr' THEN 'Entertain'
                ELSE ca_transactions.type_ca
            END as type_ca_label"),
            'ca_transactions.unit',
            DB::raw("DATE_FORMAT(ca_transactions.created_at, '%d-%M-%Y') as formatted_created_at"),
            DB::raw("DATE_FORMAT(ca_transactions.date_required, '%d-%M-%Y') as formatted_date_required"),
            DB::raw("DATE_FORMAT(ca_transactions.start_date, '%d-%M-%Y') as formatted_start_date"),
            DB::raw("DATE_FORMAT(ca_transactions.end_date, '%d-%M-%Y') as formatted_end_date"),
            DB::raw("DATE_FORMAT(ca_transactions.declare_estimate, '%d-%M-%Y') as formatted_declare_estimate"),
            'ca_transactions.contribution_level_code',
            'employees.employee_id',
            'employees.fullname',
            DB::raw("(
                    SELECT GROUP_CONCAT(DISTINCT e1.fullname ORDER BY layer ASC)
                    FROM ca_approvals ca1
                    LEFT JOIN employees e1 ON FIND_IN_SET(e1.employee_id, (
                        SELECT GROUP_CONCAT(DISTINCT employee_id ORDER BY layer ASC)
                        FROM ca_approvals
                        WHERE ca_approvals.ca_id = ca_transactions.id
                        AND role_name = 'Dept Head'
                    )) > 0
                    WHERE ca1.ca_id = ca_transactions.id
                    AND ca1.role_name = 'Dept Head'
                ) AS manager_l1_fullnames"),
            DB::raw("(
                    SELECT GROUP_CONCAT(DISTINCT e2.fullname ORDER BY layer ASC)
                    FROM ca_approvals ca2
                    LEFT JOIN employees e2 ON FIND_IN_SET(e2.employee_id, (
                        SELECT GROUP_CONCAT(DISTINCT employee_id ORDER BY layer ASC)
                        FROM ca_approvals
                        WHERE ca_approvals.ca_id = ca_transactions.id
                        AND role_name = 'Div Head'
                    )) > 0
                    WHERE ca2.ca_id = ca_transactions.id
                    AND ca2.role_name = 'Div Head'
                ) AS manager_l2_fullnames"),
            'ca_transactions.no_ca',
            'ca_transactions.no_sppd',
            'ca_transactions.total_ca',
            'ca_transactions.total_real',
            'ca_transactions.total_cost',
            'ca_transactions.approval_status',
            'ca_transactions.approval_sett',
            'ca_transactions.approval_extend',
            DB::raw("DATEDIFF(CURDATE(), ca_transactions.declare_estimate) as days_difference"),
            DB::raw("CASE
                WHEN DATEDIFF(CURDATE(), ca_transactions.declare_estimate) > 0 THEN 'Overdue'
                ELSE 'Not Overdue'
            END as overdue_status"),
            DB::raw("CASE
                WHEN DATEDIFF(CURDATE(), ca_transactions.declare_estimate) > 0 THEN ca_transactions.total_ca
                ELSE 0
            END as total_ca_adjusted"),
            DB::raw("CASE
                WHEN DATEDIFF(CURDATE(), ca_transactions.declare_estimate) BETWEEN 0 AND 6 THEN ca_transactions.total_ca
                ELSE 0
            END as total_ca_within_6_days"),
            DB::raw("CASE
                WHEN DATEDIFF(CURDATE(), ca_transactions.declare_estimate) BETWEEN 7 AND 14 THEN ca_transactions.total_ca
                ELSE 0
            END as total_ca_within_14_days"),
            DB::raw("CASE
                WHEN DATEDIFF(CURDATE(), ca_transactions.declare_estimate) BETWEEN 15 AND 30 THEN ca_transactions.total_ca
                ELSE 0
            END as total_ca_within_30_days"),
            DB::raw("CASE
                WHEN DATEDIFF(CURDATE(), ca_transactions.declare_estimate) BETWEEN 30 AND 999 THEN ca_transactions.total_ca
                ELSE 0
            END as total_ca_within_99_days")
        )
            ->join('employees', 'ca_transactions.user_id', '=', 'employees.id')
            ->leftJoin('employees as manager1', 'employees.manager_l1_id', '=', 'manager1.employee_id')
            ->leftJoin('employees as manager2', 'employees.manager_l2_id', '=', 'manager2.employee_id')
            ->groupBy('ca_transactions.id');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('ca_transactions.start_date', [$this->startDate, $this->endDate]);
        }
        if ($this->fromDate && $this->untilDate) {
            // Gunakan elseif untuk memastikan hanya salah satu rentang tanggal yang dipakai
            $query->whereBetween('ca_transactions.created_at', [$this->fromDate, $this->untilDate]);
        }
        if ($this->stat) {
            // Gunakan elseif untuk memastikan hanya salah satu rentang tanggal yang dipakai
            $query->where('ca_transactions.ca_status', [$this->stat]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Type_CA',
            'Unit',
            'Submitted Date',
            'Paid Date',
            'Start Date',
            'End Date',
            'Est. Settlement Date',
            'Company',
            'Employee ID',
            'Employee Name',
            'Dept Head',
            'Div Head',
            'Doc No',
            'Assignment',
            'Total CA',
            'Total Settlement',
            'Balance',
            'Request Status',
            'Settlement Status',
            'Extend Status',
            'Days',
            'Overdue',
            'Current',
            '< 7 Days',
            '7 - 14 Days',
            '15 - 30 Days',
            '> 30 Days',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'argb' => 'FFFFFFFF', // Warna putih
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => '228B22', // Warna kuning
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Center horizontal
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Center vertical
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow(); // Dapatkan nomor baris tertinggi
                $highestColumn = $sheet->getHighestColumn(); // Dapatkan kolom tertinggi

                // Terapkan border untuk seluruh area data
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Mengatur lebar kolom otomatis
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                }
            },
        ];
    }
}
