<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\Request;

class UpdateProfileRequest extends Request
{
	public function authorize(): bool
    {
		return true;
	}

	public function messages(): array
    {
		return [
			'email.unique'=>'E-mail already registered in the system.',
		];
	}

	public function rules(): array
    {
		return [
            'name' => 'required|string|min:4|max:255',
            'email' => 'required|email|unique:users,email,'
		];
	}
}
