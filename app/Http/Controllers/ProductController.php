<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\StoreProductImageRequest;

class ProductController extends Controller
{
    /**
     * Render the index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Product::class)) {
            abort(404);
        }
        return Inertia::render('Product/Index');
    }

    /**
     * Get filtered paginated products.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPaginated(Request $request)
    {
        if ($request->user()->cannot('viewAny', Product::class)) {
            abort(404);
        }
        $request->validate([
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'filter' => 'nullable|array|max:1', // Only supporting 1 filter
            'filter.*.field' => 'required|string|max:255',
            'filter.*.type' => 'required|string|max:255',
            'filter.*.value' => 'required|string|max:255',
        ]);
        $filter = $request->input('filter.0');
        $size = $request->input('size', 15);

        $products = Product::query();

        if ($filter && $filter['type'] === 'like' && $filter['field'] === 'name') {
            $products = $products->where('name', 'LIKE', '%' . $filter['value'] . '%');
        }

        return $products->with('defaultImage')->latest()->paginate($size, ['*']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): Response
    {
        if ($request->user()->cannot('create', Product::class)) {
            abort(404);
        }
        return Inertia::render('Product/Create');
    }

    public function imageUpload(StoreProductImageRequest $request)
    {
        $file = $request->validated()['file'];


        // Generate a secure filename for the image
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        // Store the image in the temporary directory
        $img = Image::make($file);
        $path = Storage::disk('temporary')->path($filename);
        $img->save($path, 80);

        return response()->json(['filename' => $filename]);
    }
    public function imageDelete($image)
    {
        if (!Storage::disk("temporary")->exists($image)) {
            abort(404);
        }
        Storage::disk('temporary')->delete($image);
        return to_route('dashboard');
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
            $targetWidths = ['small' => 480, 'medium' => 800, 'large' => 1200];

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
    public function edit(Requset $request, Product $product)
    {
        if ($request->cannot('edit', $product)) {
            abort(404);
        }

        return Inertia::render('Product/Edit');
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
