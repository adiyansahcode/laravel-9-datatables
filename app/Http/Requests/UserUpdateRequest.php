<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $uuid = $this->data->uuid;

        return [
            'name' => [
                'required',
                'max:100',
                'string',
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique(User::class, 'username')->ignore($uuid, 'uuid')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                function ($attribute, $value, $fail) use ($uuid) {
                    // check unique case sensitive
                    $data = User::whereNull('deleted_at')->where('uuid', '<>', $uuid)->whereRaw('LOWER(username) = ?', [Str::lower($value)])->first();
                    if ($data) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            'phone' => [
                'required',
                'numeric',
                Rule::unique(User::class, 'phone')->ignore($uuid, 'uuid')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'email' => [
                'required',
                'max:100',
                'string',
                'email:rfc,dns',
                Rule::unique(User::class, 'email')->ignore($uuid, 'uuid')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                function ($attribute, $value, $fail) use ($uuid) {
                    // check unique case sensitive
                    $data = User::whereNull('deleted_at')->where('uuid', '<>', $uuid)->whereRaw('LOWER(email) = ?', [Str::lower($value)])->first();
                    if ($data) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            'password' => [
                'nullable',
                'string',
                'alpha_dash',
                'confirmed',
                Password::min(3),
            ]
        ];
    }
}
