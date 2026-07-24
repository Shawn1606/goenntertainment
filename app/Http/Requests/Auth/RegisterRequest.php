<?php

namespace App\Http\Requests\Auth;

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
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
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
            'interests.required' => 'Bitte wähle mindestens 3 Interessen aus.',
            'interests.min' => 'Bitte wähle mindestens 3 Interessen aus.',
            'username.unique' => 'Dieser Benutzername ist bereits vergeben.',
            'username.alpha_dash' => 'Der Benutzername darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
            'email.unique' => 'Diese E-Mail-Adresse ist bereits registriert.',
            'password.min' => 'Das Passwort muss mindestens 8 Zeichen lang sein.',
            'password.letters' => 'Das Passwort muss mindestens einen Buchstaben enthalten.',
            'password.numbers' => 'Das Passwort muss mindestens eine Zahl enthalten.',
        ];
    }
}
