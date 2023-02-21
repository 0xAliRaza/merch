<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'images' => ['required', 'array', Rule::in($filenames)],
            'images.*' => 'required|string',
            'default_image_index' => 'required_with:images|nullable|integer|min:0|max:' . (count($request->images ?? []) - 1),
        ];
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'default_image_index' => 'default image',
        ];
    }
}
