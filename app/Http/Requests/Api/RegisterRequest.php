<?php

namespace App\Http\Requests\Api;

use App\AccountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()],
            'account_type' => ['required', Rule::enum(AccountType::class)],
            'interests' => ['sometimes', 'array'],
            'interests.*' => ['integer', 'exists:interests,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.unique' => 'Dieser Benutzername ist bereits vergeben.',
            'email.unique' => 'Diese E-Mail-Adresse ist bereits registriert.',
            'password.min' => 'Das Passwort muss mindestens 8 Zeichen lang sein.',
        ];
    }
}
