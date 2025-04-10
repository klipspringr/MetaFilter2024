<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;
use App\Traits\AuthStatusTrait;
use Illuminate\Validation\Rules\Password;

final class StorePasswordRequest extends BaseFormRequest
{
    use AuthStatusTrait;

    public function authorize(): bool
    {
        return $this->loggedIn();
    }

    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ];
    }
}
