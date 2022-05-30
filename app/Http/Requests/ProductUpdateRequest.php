<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public static function rules()
    {
        return [
            [
                'name' => 'required|min:2|max:100',
                'product' => Rule::exists('products', 'id'),
                'description' => 'required|min:4|max:200',
                'manufacturers' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff |max:4096']
        ];
    }
}
