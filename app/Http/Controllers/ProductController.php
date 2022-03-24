<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\CreateRequest;
use App\Http\Requests\Products\UpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::with('seller')->paginate(20);

        $products = ProductResource::collection($products);

        return $this->wrapJsonResponse($products->response(), 'Products Retrieved');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created peoduct in storage.
     *
     * @param  \Illuminate\Http\CreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        Product::create([
            'amountAvailable' => $request->quantity,
            'cost' => $request->price,
            'productName' => $request->name,
            'sellerId' => auth()->user()->userable->id
        ]);

        return $this->jsonResponse(HTTP_SUCCESS, 'Product Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->jsonResponse(HTTP_SUCCESS, 'Product Retrieved', new ProductResource($product));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Product $product)
    {
        $this->authorize('process', $product);

        if($request->filled('price')) $product->cost = $request->price;
        if($request->filled('name')) $product->productName = $request->name;
        if($request->filled('quantity')) $product->amountAvailable = $request->quantity;

        return $this->jsonResponse(HTTP_SUCCESS, 'Product Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('process', $product);

        $product->delete();

        return $this->jsonResponse(HTTP_SUCCESS, 'Product Deleted');
    }
}
