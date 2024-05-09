<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductMake;
use App\Models\ProductModel;
use App\Models\ProductDrivetrain;
use App\Models\ProductBodyType;
use App\Models\ProductEngineType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index($request = null)
    {

        // If the request is not specified, just return all products paginated
        $products = $this->filter(request(), 20, false)['products'];
        // Return a view with a list of products taking in account the search query, page, order and sort direction if they are specified
        return view('backend.products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Provide the makes models and product to the view
        return view('backend.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // Create the product
        $product = Product::create($request->validated());

        // If the request has an image, store it in the public folder and update the product's image path
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $this->storeImages($images, $product);
        }

        $product->name = $product->make->name . ' ' . $product->model->name . ' ' . $product->year;
        $product->save();

        return redirect()->route('products.edit', $product->id)->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Provide the makes models and product to the view

        return view('backend.products.edit', ['product' => $product]);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {

        // If the request has an image, store it in the public folder and update the product's image path
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $this->storeImages($images, $product);
        }

        // if delete_image array is set. remove the images from the product
        if ($request->has('delete_image')) {
            foreach ($request->delete_image as $imageId) {
                Storage::disk('public')->delete(env('APP_PRODUCTS_IMAGES_PATH') . '/' . $product->images()->find($imageId)->filename);
                $product->images()->find($imageId)->delete();
            }
        }

        // Set the name
        $product->name = $product->make->name . ' ' . $product->model->name . ' ' . $product->year;

        // Update the product with the new data
        $product->update($request->validated());

        return redirect()->route('products.edit', $product->id)->with('success', 'Product updated successfully');
    }

    public function storeImages($images, $product)
    {
        foreach ($images as $image) {
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs(env('APP_PRODUCTS_IMAGES_PATH'), $filename, 'public');
            $product->images()->create([
                'filename' => $filename,
                'sort' => 0,
            ]);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete the product
        $product->delete();
        $product->images->each(function ($image) {
            Storage::disk('public')->delete(env('APP_PRODUCTS_IMAGES_PATH') . '/' . $image->filename);
            $image->delete();
        });
        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    /**
     * Filter the products based on the request
     * @param Request $request
     * @param int $itemsPerPage
     * @param bool $getSecondaryItems
     */
    public function filter(
        Request $request = null,
        $itemsPerPage = 10,
        $getSecondaryItems = true
    ) {

        // If the request is not specified, just return all products paginated and active
        if ($request == null) {
            return [
                'products' => Product::where('active', true)->paginate($itemsPerPage),
                'makes' => [],
                'models' => [],
                'drivetrains' => [],
                'bodyTypes' => [],
                'engineTypes' => [],

            ];
        }

        $productsQuery = DB::table('products')
            ->rightJoin('product_makes', 'products.make_id', '=', 'product_makes.id')
            ->rightJoin('product_models', 'products.model_id', '=', 'product_models.id')
            ->rightJoin('product_drivetrains', 'products.drivetrain_id', '=', 'product_drivetrains.id')
            ->rightJoin('product_body_types', 'products.body_type_id', '=', 'product_body_types.id')
            ->rightJoin('product_engine_types', 'products.engine_type_id', '=', 'product_engine_types.id')
            // ->select('product_makes.name as make', 'product_models.name as model', 'product_drivetrains.name as drivetrain', 'product_body_types.name as body_type', 'product_engine_types.name as engine_type',)
            ->where('products.active', true);


        if (request()->has('search') && request('search') != null) {
            // Get db table with makes and models
            $productsQuery = $productsQuery->where(function ($query) {
                $search = request('search');
                $query->where('products.name', 'like', '%' . $search . '%')
                    ->orWhere('product_makes.name', 'like', '%' . $search . '%')
                    ->orWhere('product_models.name', 'like', '%' . $search . '%')
                    ->orWhere('products.year', 'like', '%' . $search . '%')
                    ->orWhere('products.description', 'like', '%' . $search . '%')
                    ->orWhere('product_body_types.name', 'like', '%' . $search . '%')
                    ->orWhere('product_drivetrains.name', 'like', '%' . $search . '%')
                    ->orWhere('product_engine_types.name', 'like', '%' . $search . '%');
            });
        }

        // Check if cost-from, cost-to, year-from, year-to, efficiency-from, efficiency-to are set
        $rangeFilters = ['price', 'year', 'efficiency'];
        foreach ($rangeFilters as $filter) {
            if (request()->has($filter . '-from') && request($filter . '-from') != null)
                $productsQuery = $productsQuery->where('products.' . $filter, '>=', request($filter . '-from'));
            if (request()->has($filter . '-to') && request($filter . '-to') != null)
                $productsQuery = $productsQuery->where('products.' . $filter, '<=', request($filter . '-to'));
        }



        function applyWheres($query, $except = [])
        {
            if (request()->has('makes') && !in_array('makes', $except)) {
                $query = $query->whereIn('products.make_id', request('makes'));
            }

            if (request()->has('models') && !in_array('models', $except)) {
                $query = $query->whereIn('products.model_id', request('models'));
            }

            if (request()->has('drivetrains') && !in_array('drivetrains', $except)) {
                $query = $query->whereIn('products.drivetrain_id', request('drivetrains'));
            }

            if (request()->has('body_types') && !in_array('body_types', $except)) {
                $query = $query->whereIn('products.body_type_id', request('body_types'));
            }

            if (request()->has('engine_types') && !in_array('engine_types', $except)) {
                $query = $query->whereIn('products.engine_type_id', request('engine_types'));
            }
            return $query;
        }

        if ($getSecondaryItems) {



            function mergeWithFilteredOut($query, $filteredOut, $name)
            {

                $filteredOut = $filteredOut->map(function ($item) use ($name) {
                    $filteredItem = (object) [
                        'name' => $item->name,
                        'count' => 0,
                    ];
                    if (isset($item->id))
                        $filteredItem->id = $item->id;

                    return $filteredItem;
                });
                return $query->concat($filteredOut);
            }


            $makes = $productsQuery->clone();
            $makes = applyWheres($makes, ['makes', 'models']);
            $makes = $makes->select('product_makes.id', 'product_makes.name as name', DB::raw('count(products.id) as count'))
                ->groupBy('product_makes.id')
                ->get();

            // Move selected makes to the beginning of the array

            $makes = mergeWithFilteredOut($makes, ProductMake::whereNotIn('id', $makes->pluck('id'))->get(), 'makes');
            $makes = $makes->sortByDesc(function ($make) {
                return request()->has('makes') ? in_array($make->id, request('makes')) : 0;
            });

            $models = $productsQuery->clone();
            $models = applyWheres($models, ['models']);
            $models = $models->select('product_models.id', 'product_models.name as name', DB::raw('count(products.id) as count'))
                ->groupBy('product_models.id')
                ->get();
            $models = mergeWithFilteredOut($models, ProductModel::whereNotIn('id', $models->pluck('id'))->get(), 'models');

            $drivetrains = $productsQuery->clone();
            $drivetrains = applyWheres($drivetrains, ['drivetrains']);
            $drivetrains = $drivetrains->select('product_drivetrains.id', 'product_drivetrains.name as name', DB::raw('count(products.id) as count'))
                ->groupBy('product_drivetrains.id')
                ->get();
            $drivetrains = mergeWithFilteredOut($drivetrains, ProductDrivetrain::whereNotIn('id', $drivetrains->pluck('id'))->get(), 'drivetrains');

            $bodyTypes = $productsQuery->clone();
            $bodyTypes = applyWheres($bodyTypes, ['body_types']);
            $bodyTypes = $bodyTypes->select('product_body_types.id', 'product_body_types.name as name', DB::raw('count(products.id) as count'))
                ->groupBy('product_body_types.id')
                ->get();
            $bodyTypes = mergeWithFilteredOut($bodyTypes, ProductBodyType::whereNotIn('id', $bodyTypes->pluck('id'))->get(), 'body_types');

            $engineTypes = $productsQuery->clone();
            $engineTypes = applyWheres($engineTypes, ['engine_types']);
            $engineTypes = $engineTypes->select('product_engine_types.id', 'product_engine_types.name as name', DB::raw('count(products.id) as count'))
                ->groupBy('product_engine_types.id')
                ->get();
            $engineTypes = mergeWithFilteredOut($engineTypes, ProductEngineType::whereNotIn('id', $engineTypes->pluck('id'))->get(), 'engine_types');
        }



        $productsQuery = applyWheres($productsQuery->select('products.*'));

        if (request()->has('order') && request()->has('sort')) {
            $productsQuery = $productsQuery->orderByRaw("CASE WHEN " . request('order') . " IS NULL THEN 1 ELSE 0 END, " . request('order') . " " . request('sort'));
        }


        // Paginate the products
        $products = $productsQuery->paginate($itemsPerPage);

        return [
            'products' => $products,
            'makes' => $makes ?? [],
            'models' => $models ?? [],
            'drivetrains' => $drivetrains ?? [],
            'bodyTypes' => $bodyTypes ?? [],
            'engineTypes' => $engineTypes ?? [],
        ];
    }
}
