<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->route('user');
        // dd($this->user()->can('update', $user), $user->toArray(), $this->user()->toArray(), $this->request->all());
        return $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user = $this->route('user');
        return [
            'name' => 'string|max:255',
            'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['string', 'max:15', Rule::unique('users')->ignore($user->id)],
            'password' => ['confirmed', Password::defaults()],
            'role' => [
                'string', Rule::in(User::$roles),
                function ($attribute, $value, $fail) use ($user) {
                    if ($this->user()->id === $user->id && $value !== $user->role) {
                        $fail("You are not allowed to change your own role.");
                    }
                }
            ],
        ];
    }
}
