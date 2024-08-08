<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User as UserModel;

class UserRegisterPost extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:128'],
            'email' => ['required', 'email', 'max:254'],
            'password' => ['required','confirmed', 'max:72'],
        ];
    }
}
