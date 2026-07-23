<?php

namespace App\Http\Requests\Auth;

use App\AccountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompleteGoogleRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'min:3', 'max:30', 'alpha_dash', Rule::unique('users', 'username')->ignore($this->user())],
            'account_type' => ['required', Rule::enum(AccountType::class)],
            'interests' => ['required', 'array', 'min:3'],
            'interests.*' => ['integer', 'exists:interests,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'interests.min' => 'Bitte wähle mindestens 3 Interessen aus.',
            'username.unique' => 'Dieser Benutzername ist bereits vergeben.',
        ];
    }
}
