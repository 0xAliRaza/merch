<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): Response
    {
        return Inertia::render('Product/Create');
    }

    public function imageUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png|max:10000|dimensions:min_width=1000,min_height=1000' // max 10000kb
        ], ['file.max' => 'The uploaded image must be less than 10 MB in size.',    'file.dimensions' => 'The uploaded image must have a minimum width and height of 1000 pixels.',]);


        // Generate a secure filename for the image
        $filename = uniqid() . '.' . $request->file('file')->getClientOriginalExtension();

        // Store the image in the temporary directory
        $img = Image::make($request->file('file'));
        $path = Storage::disk('temporary')->path($filename);
        $img->save($path, 80);

        return response()->json(['filename' => $filename]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $productImages = [];


        foreach ($validated['images'] as $originalImageName) {
            $productImage = new ProductImage();
            $fileContents = Storage::disk('temporary')->get($originalImageName);
            Storage::disk('images')->put($originalImageName, $fileContents);

            $productImage->original = Storage::disk("images")->url($originalImageName);

            // Create an intervention/image instance for the original image
            $image = Image::make($fileContents);

            // Define the target widths for the resized images
            $targetWidths = ['small'=> 480, 'medium'=> 800, 'large' => 1200];

            // Loop through the target widths and resize the image for each width
            foreach ($targetWidths as $widthName => $width) {

                $resizedImage = clone $image;
                $resizedImage->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                // Save the resized image to the "images" disk
                $resizedImagePath = Storage::disk('images')->path("{$widthName}/{$originalImageName}");

                // Create the directory if it does not exist
                Storage::disk('images')->makeDirectory($widthName);

                $resizedImage->save($resizedImagePath);
                $productImage->setAttribute($widthName, Storage::disk('images')->url("{$widthName}/{$originalImageName}"));
            }

            // Delete the original image from the "temporary" disk
            Storage::disk('temporary')->delete($originalImageName);

            // Push the new model to array
            array_push($productImages, $productImage);
        }
        $product = Product::create($validated);
        $product->images()->saveMany($productImages);
        $product->default_image =  $productImages[$request->input('default_image_index')]->id;
        $product->save();
        return to_route('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
