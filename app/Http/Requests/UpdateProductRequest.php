<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('product'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        $temporaryDirectory = 'temporary';

        // Get an array of filenames from the temporary directory
        $filenames = collect(Storage::files($temporaryDirectory))
            ->map(function ($filename) {
                return basename($filename);
            })
            ->toArray();
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0|regex:/^\d{0,6}(\.\d{1,2})?$/',
            'discount' => 'required|nullable|numeric|min:0|max:100',
            'new_images' => 'array|nullable',
            'new_images.*' => ['string',  Rule::in($filenames)],
            'removed_images' => 'array|nullable',
            'removed_images.*' => 'integer|exists:product_images,id',
            'default_image' => 'required_without:default_image_index|nullable|integer|exists:product_images,id',
            'default_image_index' => [
                'required_without:default_image',
                'nullable',
                'integer',
                'min:0',
                'max:' . count($request->input('new_images')) - 1
            ]
        ];
    }

    public function messages()
    {
        return [
            'default_image' => 'Please provide a default image.',
            'default_image_index' => 'Please provide a default image.',
        ];
    }
}
