<?php

namespace App\Http\Controllers\Product\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryStock;
use App\Models\Config;
use App\Models\Costing;
use App\Models\KeypadLayout;
use App\Models\Location;
use App\Models\Pricing;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockPricing;
use App\Models\StockQty;
use App\Models\SubCategory;
use App\Models\SubCategoryStock;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{


    public function index()
    {
        $categoryList = Category::all();
        $subCategoryList = SubCategory::all();
        $locationList = Location::all();
        $status_list = ['active', 'ACTIVE'];
        $ops = array(
            array(
                '$match' => array("status" => array('$in' => $status_list))
            )
        );
        $stockList = Stock::raw()->aggregate($ops)->toArray();
        $wpUrl = Config::find(1)->wp_url;
        $wpToken = Config::find(1)->wp_token;
        return view('products.list.index', array('categories' => $categoryList, 'subCategories' => $subCategoryList, 'locations' => $locationList, 'wp_url' => $wpUrl, 'wp_token' => $wpToken, 'stockList' => $stockList));
    }


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getStock(Request $request): JsonResponse
    {
        try {
            if ($request->has('id')) {
                $ops = array(

                    array(
                        '$match' => array('sku' => $request->get('id'))
                    )

                );
                $stock = Stock::raw()->aggregate($ops)->toArray();
                $soh = StockQty::raw()->aggregate($ops)->toArray();
                $stockList["stock"] = $stock;
                $stockList["soh"] = $soh;
            } else {
                $status_list = ['active', 'ACTIVE'];
                $ops = array(
                    array(
                        '$match' => array("status" => array('$in' => $status_list))
                    )
                );
                $stockList = Stock::raw()->aggregate($ops)->toArray();
            }

            return response()->json([
                'status' => 'success',
                'data' => $stockList
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveStock(Request $request): JsonResponse
    {
        try {
            $allData = $request->post();
            $stockData = $allData["stock_data"];
            $stockMainCat = $stockData["maincat"];
            $stockSubCat = $stockData["subcat"];
            $priceData = $allData["price_data"];
            $qtyData = $allData["qty_data"];

            $cat_data = array();
            $cat_data["catagoryname"] = $stockMainCat;
            $matchStockMain = CategoryStock::where('catagoryname', '=', $stockMainCat)->get()->toArray();

            if (count($matchStockMain) === 0){
                $ops = array(

                    array(
                        '$group' => array(
                            '_id' => null,
                            'maxid' => array(
                                '$max' => array(
                                    '$toDouble' => '$catid'
                                )))
                    )

                );
                $data = CategoryStock::raw()->aggregate($ops)->toArray();

                if (count($data) === 0) {
                    $cat_data["catid"] = "1";
                } else {
                    $max_id = $data[0]["maxid"];
                    $cat_data["catid"] = strval(++$max_id);
                }
                $cat_data["status"] = 'active';
                CategoryStock::raw()->insertOne($cat_data);
                $this->saveLog("saved data : " . json_encode($cat_data), CategoryStock::tableName());
                $stockData["maincat"] = $cat_data["catid"];
            }
            else{
                $stockData["maincat"] = $matchStockMain[0]["catid"];
            }



            $cat_data = array();
            $cat_data["subcatagoryname"] = $stockSubCat;

            $matchStockSub = CategoryStock::where('subcatagoryname', '=', $stockSubCat)->get()->toArray();
            if (count($matchStockSub) === 0) {
                $ops = array(

                    array(
                        '$group' => array('_id' => null, 'maxid' => array('$max' => array(
                            '$toDouble' => '$subcatid'
                        )))
                    )

                );
                $data = SubCategoryStock::raw()->aggregate($ops)->toArray();
                if (count($data) === 0) {
                    $cat_data["subcatid"] = "1";
                } else {
                    $max_id = $data[0]["maxid"];
                    $cat_data["subcatid"] = strval(++$max_id);
                }
                $cat_data["status"] = 'active';
                SubCategoryStock::raw()->insertOne($cat_data);
                $this->saveLog("saved data : " . json_encode($cat_data), SubCategoryStock::tableName());
                $stockData["subcat"] = $cat_data["subcatid"];

            }
            else{
                $stockData["subcat"] = $matchStockSub[0]["subcatid"];
            }


            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$sku'
                            )))
                )

            );
            $data = Stock::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $stockData["sku"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $stockData["sku"] = strval(++$max_id);
            }
            $stockData["status"] = 'active';

            Stock::raw()->insertOne($stockData);
            $this->saveLog("saved data : " . json_encode($stockData), Stock::tableName());

            //        insert data to stock pricing table

            foreach ($priceData as $c_data) {
                $c_data["sku"] = $stockData["sku"];
                StockPricing::raw()->insertOne($c_data);
                $this->saveLog("saved data : " . json_encode($c_data), StockPricing::tableName());
            }
            //        insert data to stock qty table
            foreach ($qtyData as $c_data) {
                $c_data["sku"] = $stockData["sku"];
                $c_data["stockqty"] = "0";
                StockQty::raw()->insertOne($c_data);
                $this->saveLog("saved data : " . json_encode($c_data), StockQty::tableName());
            }
            return response()->json([
                'status' => 'success',
                'data' => $stockData["sku"]
            ]);


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProductWeb(Request $request): JsonResponse
    {
        try {

            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('sku' => $id)
                    )

                );
                $data = Product::raw()->aggregate($ops)->toArray();
                $costsData = Costing::raw()->aggregate($ops)->toArray();
                $pricingData = Pricing::raw()->aggregate($ops)->toArray();
                return response()->json([
                    'status' => 'success',
                    'data' => array(
                        'product_data' => $data,
                        'cost_data' => $costsData,
                        'pricing_data' => $pricingData
                    )
                ]);
            } else {
                $data = Location::all()->toArray();
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
            }


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProduct(Request $request): JsonResponse
    {
        try {
            $categoryTable = Category::tableName();
            $subCategoryTable = SubCategory::tableName();

            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('sku' => $id)
                    )

                );
                $data = Product::raw()->aggregate($ops)->toArray();
                $costsData = Costing::raw()->aggregate($ops)->toArray();
                $pricingData = Pricing::raw()->aggregate($ops)->toArray();
                return response()->json([
                    'status' => 'success',
                    'data' => array(
                        'product_data' => $data,
                        'cost_data' => $costsData,
                        'pricing_data' => $pricingData
                    )
                ]);
            } else if ($request->has('status')) {
                $show_status = $request->get('status');


                if (strcmp($show_status, "all") == 0) {

                    $ops = array(
                        array(
                            '$lookup' => array(
                                "from" => $categoryTable,
                                "localField" => "maincat",
                                "foreignField" => "catid",
                                "as" => "catagory"
                            )
                        ),
                        array(
                            '$unwind' => '$catagory'
                        ),
                        array(
                            '$lookup' => array(
                                "from" => $subCategoryTable,
                                "localField" => "subcat",
                                "foreignField" => "subcatid",
                                "as" => "subcatagory"
                            )
                        ),
                        array(
                            '$unwind' => '$subcatagory'
                        ),

                    );
                } else {
                    $ops = array(
                        array(
                            '$match' => array('status' => $show_status)
                        ),
                        array(
                            '$lookup' => array(
                                "from" => $categoryTable,
                                "localField" => "maincat",
                                "foreignField" => "catid",
                                "as" => "catagory"
                            )
                        ),
                        array(
                            '$unwind' => '$catagory'
                        ),
                        array(
                            '$lookup' => array(
                                "from" => $subCategoryTable,
                                "localField" => "subcat",
                                "foreignField" => "subcatid",
                                "as" => "subcatagory"
                            )
                        ),
                        array(
                            '$unwind' => '$subcatagory'
                        ),

                    );
                }
                $data = Product::raw()->aggregate($ops)->toArray();
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
            } else {
                $ops = array(
                    array(
                        '$lookup' => array(
                            "from" => $categoryTable,
                            "localField" => "maincat",
                            "foreignField" => "catid",
                            "as" => "catagory"
                        )
                    ),
                    array(
                        '$unwind' => '$catagory'
                    ),
                    array(
                        '$lookup' => array(
                            "from" => $subCategoryTable,
                            "localField" => "subcat",
                            "foreignField" => "subcatid",
                            "as" => "subcatagory"
                        )
                    ),
                    array(
                        '$unwind' => '$subcatagory'
                    ),

                );
                $data = Product::raw()->aggregate($ops)->toArray();
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
            }


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveImage(Request $request): JsonResponse
    {
        try {

            $saveImage = url("/uploads/web-info");

            $destinationPath = public_path() . '/uploads/web-info/'; // make sure to create this directry in public_html (Apache, Cpanel) OR public (nginx)

            $image = $request->file('saveImg');

            if ($image && $image->isValid()) {
                $fileExt = $image->extension();
                $fileName = md5(microtime());
                $image->move($destinationPath, $fileName . "." . $fileExt);
                $saveImage = $saveImage . '/' . $fileName . "." . $fileExt;
            } else {
                $saveImage = $saveImage . "/default-avatar.jpg";
            }
            $this->saveLog("saved image : " . $saveImage, "");
            return response()->json([
                'status' => 'success',
                'data' => $saveImage
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveProduct(Request $request): JsonResponse
    {
        try {
            $posImage = url("/uploads/web-info");
            $mainImage = url("/uploads/web-info");
            $galleryImage1 = url("/uploads/web-info");
            $galleryImage2 = url("/uploads/web-info");
            $galleryImage3 = url("/uploads/web-info");
            $galleryImage4 = url("/uploads/web-info");

            $destinationPath = public_path() . '/uploads/web-info/'; // make sure to create this directry in public_html (Apache, Cpanel) OR public (nginx)

            $image = $request->file('pos-image');

            if ($image && $image->isValid()) {
                $fileExt = $image->extension();
                $fileName = md5(microtime());
                $image->move($destinationPath, $fileName . "." . $fileExt);
                $posImage = $posImage . '/' . $fileName . "." . $fileExt;
            } else {
                $posImage = $posImage . "/default-avatar.jpg";
            }



            $image = $request->file('mainImg');

            if ($image && $image->isValid()) {
                $fileExt = $image->extension();
                $fileName = md5(microtime());
                $image->move($destinationPath, $fileName . "." . $fileExt);
                $mainImage = $mainImage . '/' . $fileName . "." . $fileExt;
            } else {
                $mainImage = $mainImage . "/default-avatar.jpg";
            }

            $image = $request->file('gallery1');

            if ($image && $image->isValid()) {
                $fileExt = $image->extension();
                $fileName = md5(microtime());
                $image->move($destinationPath, $fileName . "." . $fileExt);
                $galleryImage1 = $galleryImage1 . '/' . $fileName . "." . $fileExt;
            } else {
                $galleryImage1 = $galleryImage1 . "/default-avatar.jpg";
            }

            $image = $request->file('gallery2');

            if ($image && $image->isValid()) {
                $fileExt = $image->extension();
                $fileName = md5(microtime());
                $image->move($destinationPath, $fileName . "." . $fileExt);
                $galleryImage2 = $galleryImage2 . '/' . $fileName . "." . $fileExt;
            } else {
                $galleryImage2 = $galleryImage2 . "/default-avatar.jpg";
            }

            $image = $request->file('gallery3');
            $fileExt = $image->extension();
            if ($image && $image->isValid()) {
                $fileName = md5(microtime());
                $image->move($destinationPath, $fileName . "." . $fileExt);
                $galleryImage3 = $galleryImage3 . '/' . $fileName . "." . $fileExt;
            } else {
                $galleryImage3 = $galleryImage3 . "/default-avatar.jpg";
            }

            $image = $request->file('gallery4');

            if ($image && $image->isValid()) {
                $fileExt = $image->extension();
                $fileName = md5(microtime());
                $image->move($destinationPath, $fileName . "." . $fileExt);
                $galleryImage4 = $galleryImage4 . '/' . $fileName . "." . $fileExt;
            } else {
                $galleryImage4 = $galleryImage4 . "/default-avatar.jpg";
            }


            $product_data = $request->post('data');
            $product_data = json_decode($product_data);
            if (isset($product_data->c_id)) {
                $pro_data = $product_data->c_data->product_data;
                $pro_data->status = "active";
                $pro_data->pos_image = $posImage;
                $pro_data->main_image = $mainImage;
                $pro_data->gallery1 = $galleryImage1;
                $pro_data->gallery2 = $galleryImage2;
                $pro_data->gallery3 = $galleryImage3;
                $pro_data->gallery4 = $galleryImage4;
                $price_data = $product_data->c_data->price_data;
                $update_data = array('$set' => $pro_data);
                $condition = array('sku' => strval($product_data->c_id));
                Product::raw()->updateOne($condition, $update_data, ['upsert' => true]);

                $this->saveLog("reference id: " . $product_data->c_id . " updated data : " . json_encode($update_data), Product::tableName());


                //        insert data to pricing table
                $prices_data = $price_data->prices;

                foreach ($prices_data as $c_data) {
                    $update_data = array('$set' => $c_data);
                    $condition = array('sku' => strval($product_data->c_id), "storeid" => strval($c_data->storeid));
                    Pricing::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    $this->saveLog("sku: " . $product_data->c_id . ", storeid : " . $c_data->storeid . ", updated data : " . json_encode($update_data), Pricing::tableName());
                }

                $cost_data = $price_data->costs;

                foreach ($cost_data as $c_data) {
                    $update_data = array('$set' => $c_data);
                    $condition = array('sku' => strval($product_data->c_id), "storeid" => strval($c_data->storeid));
                    Costing::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    $this->saveLog("sku: " . $product_data->c_id . ", storeid : " . $c_data->storeid . ", updated data : " . json_encode($update_data), Costing::tableName());
                }
            } else {
                $pro_data = $product_data->product_data;
                $price_data = $product_data->price_data;
//        insert data to products table;
                $ops = array(

                    array(
                        '$group' => array(
                            '_id' => null,
                            'maxid' => array(
                                '$max' => array(
                                    '$toDouble' => '$sku'
                                )))
                    )

                );
                $data = Product::raw()->aggregate($ops)->toArray();
                if (count($data) === 0) {
                    $pro_data->sku = "1";
                } else {
                    $max_id = $data[0]["maxid"];
                    $pro_data->sku = strval(++$max_id);
                }
                $pro_data->status = "active";
                $pro_data->pos_image = $posImage;
                $pro_data->main_image = $mainImage;
                $pro_data->gallery1 = $galleryImage1;
                $pro_data->gallery2 = $galleryImage2;
                $pro_data->gallery3 = $galleryImage3;
                $pro_data->gallery4 = $galleryImage4;



                Product::raw()->insertOne($pro_data);
                $this->saveLog("saved data : ".json_encode($pro_data), Product::tableName());

                $cost_data = $price_data->costs;

                foreach ($cost_data as $c_data) {
                    $c_data->sku = $pro_data->sku;

                    $update_data = array('$set' => $c_data);
                    $condition = array('sku' => $pro_data->sku, 'storeid' => $c_data->storeid);
                    Costing::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    $this->saveLog("sku: " . $pro_data->sku . ", storeid : " . $c_data->storeid . ", updated data : " . json_encode($update_data), Costing::tableName());

                }
                //        insert data to pricing table
                $prices_data = $price_data->prices;

                foreach ($prices_data as $c_data) {
                    $c_data->sku = $pro_data->sku;
                    $update_data = array('$set' => $c_data);
                    $condition = array('sku' => $pro_data->sku, 'storeid' => $c_data->storeid);
                    Pricing::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    $this->saveLog("sku: " . $pro_data->sku . ", storeid : " . $c_data->storeid . ", updated data : " . json_encode($update_data), Pricing::tableName());
                }
            }

            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calculateCost(Request $request): JsonResponse
    {
        try {
            $costData = array();
            $sohData = array();
            $location_data = Location::all();
            $costData[0] = 0;
            foreach ($location_data as $location) {
                $costData[$location->locationid] = 0;
            }

            $sohData[0] = array();
            foreach ($location_data as $location) {
                $sohData[$location->locationid] = array();
            }

            $stock_post_data = $request->post('data');


            foreach ($stock_post_data as $stock) {
                $stock_sku = $stock[0];
                $stock_qty = (float)$stock[1];
                $stock_unit = (float)$stock[2];

                $ops = array(
                    array(
                        '$match' => array("sku" => $stock_sku)
                    )
                );

                $stock_data = Stock::raw()->aggregate($ops)->toArray();
                $stock_pricing_data = StockPricing::raw()->aggregate($ops)->toArray();
                $stock_qty_data = StockQty::raw()->aggregate($ops)->toArray();
//                array_push($sohData[0], (float)$stock_data[0]->baseqty / (float)$stock_unit);
                array_push($sohData[0], "");
                foreach ($stock_qty_data as $stock_qty_d) {
                    if (array_key_exists($stock_qty_d->storeid, $sohData)){
                        array_push($sohData[$stock_qty_d->storeid], (float)$stock_qty_d->stockqty / (float)$stock_unit);
                    }

                }


                if (count($stock_data) === 0) {
                    echo json_encode(array('status' => 'fail'));
                } else {

                    switch ($stock_data[0]->stockoption) {
                        case "each" :
//                            $costData[0] += (float)$stock_data[0]->baseprice * (float)$stock_data[0]->baseqty;
                            $costData[0] += 0;
                            foreach ($stock_pricing_data as $stock_pricing) {
                                if (array_key_exists($stock_pricing->storeid, $costData)) {
                                    $costData[$stock_pricing->storeid] += (float)$stock_pricing->stockprice * (float)$stock_qty;
                                }
                            }

                            break;
                        case "kgs":
                        case "grams":
                        case "mls" :
//                            $costData[0] += (float)$stock_data[0]->baseprice * (float)$stock_data[0]->baseqty / (float)$stock_unit;
                            $costData[0] += 0;
                            foreach ($stock_pricing_data as $stock_pricing) {
                                if (array_key_exists($stock_pricing->storeid, $costData)) {
                                    $costData[$stock_pricing->storeid] += (float)$stock_pricing->stockprice * (float)$stock_qty / (float)$stock_unit;
                                }

                            }
                            break;
                        default:
                            break;
                    }
                }

            }

            foreach ($sohData as $key => $soh) {
                if (count($soh) !== 0){
                    $sohData[$key] = min($soh);
                }
                else{
                    $sohData[$key] = 0;
                }
            }

            $result = [];
            $result["costData"] = $costData;
            $result["sohData"] = $sohData;

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }

    public function destroy($id): JsonResponse
    {
        try {

            $check_json = false;

            $keypadData = KeypadLayout::all();

            $data_ref = "S-" . strval($id);

            foreach ($keypadData as $key => $row) {
                if (strpos($row->jsonlayout, $data_ref) !== false) {
                    $check_json = true;
                }
            }
            if (!$check_json) {
                $update_data = array('$set' => array('status' => 'inactive'));
                $condition = array('sku' => strval($id));
                Product::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                $this->saveLog("deleted data (reference id): " . $id, Product::tableName());
                return response()->json([
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'status' => 'fail'
                ]);
            }


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update($id): JsonResponse
    {
        try {

            $update_data = array('$set' => array(
                'status' => 'active'
            ));
            $condition = array('sku' => strval($id));
            Product::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Product::tableName());
            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getAllAjax(Request $request)
    {
        try {
            $pricingTable    = Pricing::tableName();

            $resultCount      = 20;
            $page             = $request->get('page');
            $offset           = ($page - 1) * $resultCount;
            $term             = trim(strtolower($request->get("term")));

            if(!empty($term)){
                $ops = array(
                    array(
                        '$lookup' => array(
                            "from"         => $pricingTable,
                            // "localField"   => "sku",
                            // "foreignField" => "sku",
                            "as"           => $pricingTable,
                            "let" => array(
                                "sku" => '$sku',
                                'storeid' => '$storeid',
                            ),
                            'pipeline' => array(
                                array(
                                    '$match' => array(
                                        '$expr' => array(
                                            '$and' => array(
                                                array(
                                                    '$eq' => array(
                                                        '$sku',
                                                        '$$sku',
                                                    ),
                                                ),
                                                array(
                                                    '$eq' => array(
                                                        '$storeid',
                                                        $request->get('storeId'), //'$$storeid',
                                                    ),
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        )
                    ),
                    array(
                        '$unwind' => '$'.$pricingTable
                    ),
                    array(
                        '$match' => array("productname" => array('$regex' => "$term", '$options' => 'i'))
                    ),
                );
            } else{
                $ops = array(
                    array(
                        '$lookup' => array(
                            "from"         => $pricingTable,
                            // "localField"   => "sku",
                            // "foreignField" => "sku",
                            "as"           => $pricingTable,
                            "let" => array(
                                "sku" => '$sku',
                                'storeid' => '$storeid',
                            ),
                            'pipeline' => array(
                                array(
                                    '$match' => array(
                                        '$expr' => array(
                                            '$and' => array(
                                                array(
                                                    '$eq' => array(
                                                        '$sku',
                                                        '$$sku',
                                                    ),
                                                ),
                                                array(
                                                    '$eq' => array(
                                                        '$storeid',
                                                        $request->get('storeId'), //'$$storeid',
                                                    ),
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        )
                    ),
                    array(
                        '$unwind' => '$'.$pricingTable
                    ),
                );
            }



            $products = Product::raw()->aggregate($ops)->toArray();

            $count     = count($products);
            $endCount  = $offset + $resultCount;
            $morePages = $count > $endCount;

            foreach ($products as &$product) {
                $product['id']   = $product['sku'];
                $product['text'] = $product['productname'];
            }

            return response()->json(array("results" => $products, "pagination" => array("more" => $morePages)));
        }catch(\Throwable $throwable){
            return $throwable->getMessage();
            return response()->json(array("results" => collect([]), "pagination" => array("more" => false)));
        }
    }
}
