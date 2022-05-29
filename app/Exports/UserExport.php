<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Ramsey\Uuid\Type\Integer;

class UserExport implements FromQuery, Responsable, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStrictNullComparison, WithTitle
{
    use Exportable;

    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;

    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function __construct(string $type)
    {
        $this->type = $type;
        $this->fileName = $this->setFileName();
    }

    /**
     * @return string
     */
    public function setFileName(): string
    {
        $fileName = $this->type . '-list-' . now()->isoFormat('YMMDD') . '.xlsx';
        return $fileName;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->type . ' list';
    }

    public function headings(): array
    {
        return [
            'created at',
            'updated at',
            'name',
            'username',
            'phone',
            'email',
            'status',
        ];
    }

    /**
    * @var User $row
    */
    public function map($row): array
    {
        if ($row->is_active) {
            $status = 'active';
        } else {
            $status = 'non active';
        }

        return [
            Date::dateTimeToExcel($row->created_at),
            Date::dateTimeToExcel($row->updated_at),
            $row->name,
            $row->username,
            $row->phone,
            $row->email,
            $status,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'B' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'C' => NumberFormat::FORMAT_GENERAL,
            'D' => NumberFormat::FORMAT_GENERAL,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_GENERAL,
            'G' => NumberFormat::FORMAT_GENERAL,
        ];
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return User::query();
    }
}
