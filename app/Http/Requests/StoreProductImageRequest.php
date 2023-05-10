<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Product::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'file' => 'required|image|mimes:jpeg,jpg,png|max:10000|dimensions:min_width=1000,min_height=1000,' // max 10000kb & 1000px x 1000px
        ];
    }


    /**
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'file.max' => 'The uploaded image must be less than 10 MB in size.',
            'file.dimensions' => 'The uploaded image must have a minimum width and height of 1000 pixels.',
        ];
    }
}
