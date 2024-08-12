<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Dotenv\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index(){
        $products = Product::get();
        if($products){
            return ProductResource::collection($products);
        }
        else{
            return response()->json(['message' => 'Products not found'],200  );
        }

    }
    public function store(Request $request){
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|integer',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'All fields are required',
                'errors' => $validator->errors()
            ],422);
        }
     $product =    Product::create([
            'name'=> $request->name,
            'description'=> $request->description,
            'price'=> $request->price
        ]);

     return response()->json([
         'message' => 'Product created successfully',
         'data'=> new ProductResource($product)
     ],200);
    }
    public function show(Product $product){
    return new ProductResource($product);
    }
    public function update(Request $request, Product $product){
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|integer',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'All fields are required',
                'errors' => $validator->errors()
            ],422);
        };
        $product->update([
            'name'=> $request->name,
            'description'=> $request->description,
            'price'=> $request->price
        ]);

        return response()->json([
            'message' => 'Product Updated successfully',
            'data'=> new ProductResource($product)
        ],200);

    }
    public function destroy(Product $product){
        $product->delete();
        return response()->json([
            "message" => "Product deleted successfully"
        ],200);
    }
}
