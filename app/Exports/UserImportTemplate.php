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
use Illuminate\Support\Collection;

class UserImportTemplate implements FromCollection, Responsable, ShouldAutoSize, WithHeadings, WithStrictNullComparison, WithTitle
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
        $fileName = $this->type . '-template.xlsx';
        return $fileName;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->type . ' template';
    }

    public function headings(): array
    {
        return [
            'name',
            'username',
            'phone',
            'email',
            'status',
            'password',
        ];
    }

    public function collection()
    {
        return new Collection([
            ['name value', 'username value', 'phone value', 'email value', 'status value: active or non active', 'password value'],
        ]);
    }
}
