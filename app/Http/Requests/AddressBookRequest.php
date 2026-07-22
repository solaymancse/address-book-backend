<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressBookRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        // Safely retrieve the model or route parameter id
        $routeParam = $this->route('address_book');
        $id = is_object($routeParam) ? $routeParam->id : $routeParam;

        $uniqueEmailRule = 'unique:address_books,email';
        if (!empty($id)) {
            $uniqueEmailRule .= ',' . $id;
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', $uniqueEmailRule],
            'website' => ['nullable', 'url', 'max:255'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'nationality' => ['required', 'string', 'max:100'],
        ];
    }
}