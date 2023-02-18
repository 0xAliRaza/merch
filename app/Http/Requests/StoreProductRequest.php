<?php

namespace App\Http\Requests;

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
        return true;
        dd($this->user()->can('create'));
        return $this->user()->can('create');
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
            'main_image' => 'required|integer|min:0|max:' . (count($request->images) - 1),
        ];
    }

    // protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     $errors = $validator->errors()->toArray();

    //     // Merge error messages for images field and its child rules
    //     $imageErrors = array_merge(
    //         $errors['images'] ?? [],
    //         ...array_values($errors['images.*'] ?? [])
    //     );
    //     // // Convert image error messages to an associative array
    //     $imageErrorMessages = [];
    //     foreach ($imageErrors as $field => $message) {
    //         $fieldParts = explode('.', $field);
    //         $index = end($fieldParts);
    //         $imageErrorMessages[$index] = $message;
    //     }

    //     dd ($imageErrorMessages);
    //     // Return image error messages in object format
    //     if (!empty($imageErrorMessages)) {
    //         $response = response()->json(['images' => $imageErrorMessages], 422);
    //         throw new \Illuminate\Validation\ValidationException($validator, $response);
    //     }

    //     parent::failedValidation($validator);
    // }
}
