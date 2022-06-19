<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with('product_variant_prices','product_variants')->paginate(3);
       //return $products;
        return view('products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
      
        DB::transaction(function() use($request) {
           
            $product = new Product();

            $product->title = $request->title;
            $product->sku = $request->sku;
            $product->description = $request->description;

            $product->save();

            //Save product variants data
            $productVariant = new ProductVariant();

            $countVariant = count($request->product_variant);
  
            if($countVariant !== null){
              for ($i=0; $i < $countVariant ; $i++) { 
                $countTags = count($request->product_variant[$i]['tags']);

                for($j=0; $j < $countTags ; $j++){
                    $productVariant = new ProductVariant();
  
                    $productVariant->variant_id = $request->product_variant[$i]['option'];
                    $productVariant->variant = $request->product_variant[$i]['tags'][$j];
                    $productVariant->product_id = $product->id;
    
                    $productVariant->save();
                }
               
              }
            }

            //Save product variants data
            $productVariantPrice = new ProductVariantPrice();

            $countVariantPrice = count($request->product_variant_prices);
  
            if($countVariantPrice !== null){
              for ($i=0; $i < $countVariantPrice ; $i++) { 

                    $productVariant = new ProductVariant();
  
                    $productVariantPrice->product_variant_one = 1;
                    $productVariantPrice->product_variant_two =2;
                    $productVariantPrice->product_variant_three = 3;
                    $productVariantPrice->price = $request->product_variant_prices[$i]['price'];
                    $productVariantPrice->stock = $request->product_variant_prices[$i]['stock'];
                    $productVariantPrice->product_id = $product->id;
    
                    $productVariantPrice->save();
               
              }
            }

            // Save product image
            $productImage = new ProductImage();
            if($request->product_image){
                $image = $request->product_image;
                $image_thumbnail = $request->product_image;
                $img_name = date('YmdHi').$image->getClientOriginalName();
                $image->move(public_path("backend/uploads/products"),$img_name);
                $image_thumbnail->move(public_path("backend/uploads/thumbnail"),$img_name);

                $productImage['file_path'] = $img_name;
                $productImage['thumbnail'] = $img_name;
                $productImage->product_id = $product->id;
            }

        });
        
        return response()->json([
            'message' => 'Product added successfull',
            'status' => 200
        ]);

        //return redirect()->route('student.registration.view')->with($notification);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product,$id)
    {
        $variants = Variant::all();
        $editProduct = Product::with('product_variant_prices','product_variants')->find($id);
        return view('products.edit', compact('variants','editProduct'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
