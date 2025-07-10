<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $contactId = $this->route('id') ?? $this->route('contact');

        return [
            'name' => 'required|min:3|unique:contacts,name,' . $contactId,
            'email' => 'required|email|unique:contacts,email,' . $contactId,
            'phone' => ['required', 'regex:/^\d{7,11}$/', 'unique:contacts,phone,' . $contactId],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/\D/', '', $this->input('phone')),
            ]);
        }
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->header('X-Inertia')) {
            throw new HttpResponseException(
                redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
            );
        }

        parent::failedValidation($validator);
    }
}
