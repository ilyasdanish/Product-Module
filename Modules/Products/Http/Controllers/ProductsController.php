<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Products\Emails\ProductAdded;
use Modules\Products\Entities\Product;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $products = Product::query();

        if ($request->has('name')) {
            $products->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('added_by')) {
            $products->where('added_by', $request->input('added_by'));
        }

        $products = $products->with('addedBy')->get();


        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:item,service',
        ]);

        try {

            $user = auth()->user();
            $product = new Product();
            $product->name = $validatedData['name'];
            $product->price = $validatedData['price'];
            $product->status = $validatedData['status'];
            $product->added_by = $user->id;
            $product->type = $validatedData['type'];
            $product->save();

            $changes = [
                'name' => ['old' => null, 'new' => $product->name],
                'price' => ['old' => null, 'new' => $product->price],
                'status' => ['old' => null, 'new' => $product->status],
                'type' => ['old' => null, 'new' => $product->type],
            ];

            $product->logChanges($changes, $user);


            // Send email notification to the user who added the product
            Mail::to($user)->send(new ProductAdded($product));

            return response()->json(['data' => $product], 201);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['message' => 'Error:' . $e->getMessage()]);
        }
    }

    /**
     * Show the specified resource.
     * @param Product $product
     * @return Renderable
     */
    public function show(Product $product)
    {
        $product->load(['addedBy']);
        return response()->json($product);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Product $product
     * @return Renderable
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:item,service',
        ]);

        $user = auth()->user();

        $changes = [
            'name' => ['old' => $product->name, 'new' => $request->name],
            'price' => ['old' => $product->price, 'new' => $request->price],
            'status' => ['old' => $product->status, 'new' => $request->status],
            'type' => ['old' => $product->type, 'new' => $request->type],
        ];

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'status' => $request->status,
            'type' => $request->type,
            'added_by' => $user->id,
        ]);

        $product->logChanges($changes, $user);


        return response()->json($product);
    }


    /**
     * Remove the specified resource from storage.
     * @param Product $product
     * @return Renderable
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
