<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    const PRODUCT_ERROR_MESSAGES = [
        'name.required' => 'Название продукта обязательна.',
        'description.required' => 'Описание продукта обязательна.',
        'description.min' => 'Минимальное название продукта 4 символа.',
        'description.max' => 'Максимальное название продукта 200 символа.',
        'name.min' => 'Минимальное название продукта 2 символа.',
        'name.max' => 'Максимальное название продукта 100 символа.',
        'manufacturers.*' => 'Выбор компании обязательна.'
    ];

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
            'name' => 'required|min:2|max:100',
            'description' => 'required|min:4|max:200',
            'manufacturers' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,bmp,tiff |max:4096',
        ];
    }

}
