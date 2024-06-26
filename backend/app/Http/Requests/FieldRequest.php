<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FieldRequest extends DefaultRequest
{
   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,jfif|max:2048',
            'price' => 'required|numeric|min:0',
            'field_type' => 'required|string|max:255',
            'owner_id' => 'required|integer',
            'availablity' => 'required|boolean',
        ];
    }
}
