<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements ToModel, SkipsEmptyRows, WithBatchInserts, WithChunkReading, WithStartRow, WithValidation
{
    use Importable;

    /**
     * @return array
     */
    public function customValidationAttributes()
    {
        return [
            '0' => 'name',
            '1' => 'username',
            '2' => 'phone',
            '3' => 'email',
            '4' => 'status',
            '5' => 'password'
        ];
    }

    /**
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '0' => [
                'required',
                'max:100',
                'string',
            ],
            '1' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique(User::class, 'username')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                function ($attribute, $value, $fail) {
                    // check unique case sensitive
                    $data = User::whereNull('deleted_at')->whereRaw('LOWER(username) = ?', [Str::lower($value)])->first();
                    if ($data) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            '2' => [
                'required',
                'numeric',
                Rule::unique(User::class, 'phone')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            '3' => [
                'required',
                'max:100',
                'string',
                'email:rfc,dns',
                Rule::unique(User::class, 'email')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                function ($attribute, $value, $fail) {
                    // check unique case sensitive
                    $data = User::whereNull('deleted_at')->whereRaw('LOWER(email) = ?', [Str::lower($value)])->first();
                    if ($data) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            '5' => [
                'required',
                'string',
                'alpha_dash',
                Password::min(3),
            ]
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row[4] === 'active') {
            $status = '1';
            $activatedAt = now()->toDateTimeString();
            $deactivatedAt = null;
        } else {
            $status = '0';
            $activatedAt = null;
            $deactivatedAt = now()->toDateTimeString();
        }

        return new User([
            'uuid' => (string) Str::uuid(),
            'name' => $row[0],
            'username' => $row[1],
            'phone' => $row[2],
            'email' => $row[3],
            'is_active' => $status,
            'password' => $row[5],
            'activated_at' => $activatedAt,
            'deactivated_at' => $deactivatedAt,
         ]);
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
