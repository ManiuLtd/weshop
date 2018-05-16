<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductVariable;
use App\Models\ProductCategory;
use App\Models\ProductDetail;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::with(["variable", "detail", "prices"])
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.product.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product->locale_id = $request->input("locale_id", 1);
        $product->name = $request->name;
        $product->brand_id = $request->brand_id;
        $product->model = $request->model;
        $product->storage_id = $request->storage_id;
        $product->content = $request->content;
        $product->measure_unit = $request->measure_unit;
        $product->packing_unit = $request->packing_unit;
        $product->sort_order = $request->input("sort_order", 1000);
        $s0 = $product->save();
        if (!$s0) {
            return ["err" => "save product error"];
        }

        // save product price
        $price = new ProductPrice();
        $price->fill([
            "product_id" => $product->id,
            "unit_price" => $request->unit_price,
        ]);
        $s1 = $price->save();
        if (!$s1) {
            $product->delete();
            return ["err" => "save price error"];
        }

        // save product category
        $category = new ProductCategory();
        $category->fill([
            "product_id" => $product->id,
            "category_id" => $request->input("category_id"),
            "is_primary" => 1,
        ]);
        $s2 = $category->save();
        if (!$s2) {
            $price->delete();
            $product->delete();
            return ["err" => "save category error"];
        }
        
        // save product variable
        $variable = new ProductVariable();
        $variable->fill([
            "product_id" => $product->id,
            "unit_price" => $request->unit_price,
            "stock" => $request->input("stock", 0),
        ]);
        $s3 = $variable->save();
        if (!$s3) {
            $category->delete();
            $price->delete();
            $product->delete();
            return ["err" => "save variable error"];
        }
               
        // save product detail
        $detail = new ProductDetail();
        $detail->fill([
            "product_id" => $product->id,
            "content" => $request->input("detail", null),
        ]);
        $s4 = $detail->save();
        if (!$s4) {
            $variable->delete();
            $category->delete();
            $price->delete();
            $product->delete();
            return ["err" => "save detail error"];
        }
        
        return [
            "store" => $product->id,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view("admin.product.show", ["product" => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view("admin.product.edit", ["product" => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // update product
        $product->locale_id = $request->input("locale_id", 1);
        $product->name = $request->name;
        $product->brand_id = $request->brand_id;
        $product->model = $request->model;
        $product->storage_id = $request->storage_id;
        $product->content = $request->content;
        $product->measure_unit = $request->measure_unit;
        $product->packing_unit = $request->packing_unit;
        $product->sort_order = $request->input("sort_order", 1000);
        $s0 = $product->save();

        // save product price
        if ($request->unit_price != $product->price()->unit_price) {
            $price = new ProductPrice();
            $price->fill([
                "product_id" => $product->id,
                "unit_price" => $request->unit_price,
            ]);
            $s1 = $price->save();
            event(new ProductPriceChangedEvent($product));
        } else {
            $s1 = true;
        }

        // 以下过程未做判断，有些是非必须的
        // save product primary category
        $category = $product->category();
        $category->category_id = $request->category_id;
        $s2 = $category->save();
        
        // save product variable
        $variable = $product->variable;
        $variable->stock = $request->stock;
        $variable->unit_price = $request->unit_price;
        $s3 = $variable->save();
               
        // save product detail
        $detail = $product->detail;
        $detail->content = $request->input("detail", null);
        $detail->save();
        $s4 = $detail->save();

        return ["update" => $s0 & $s1 & $s2 & $s3 & $s4];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}
