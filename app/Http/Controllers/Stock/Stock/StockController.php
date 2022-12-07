<?php

namespace App\Http\Controllers\Stock\Stock;

use App\Http\Controllers\Controller;
use App\Models\CategoryStock;
use App\Models\Costing;
use App\Models\Location;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockPricing;
use App\Models\StockQty;
use App\Models\SubCategoryStock;
use App\Models\Supplier;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        //
        $categoryList = CategoryStock::all();
        $subCategoryList = SubCategoryStock::all();
        $locationList = Location::all();
        $suppliers = Supplier::all();
        return view('stocks.list.index', array('categories' => $categoryList, 'subCategories' => $subCategoryList, 'locations' => $locationList, "suppliers" => $suppliers));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveStock(Request $request): JsonResponse
    {
        try {
            $stock_data = $request->post('data');
//        get product data and price data
            $pro_data = $stock_data["stock_data"];

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
            $data = Stock::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $pro_data["sku"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $pro_data["sku"] = strval(++$max_id);
            }
            $pro_data["status"] = "active";

            Stock::raw()->insertOne($pro_data);
            $this->saveLog("saved data : ".json_encode($pro_data), Stock::tableName());

            //        insert data to pricing table
            if (isset($stock_data["price_data"])) {
                $price_data = $stock_data["price_data"];
                $prices_data = $price_data["prices"];

                foreach ($prices_data as $c_data) {
                    $c_data["sku"] = $pro_data["sku"];
                    $update_data = array('$set' => $c_data);
                    $condition = array('sku' => strval($pro_data["sku"]), "storeid" => strval($c_data["storeid"]));
                    StockPricing::raw()->updateOne($condition, $update_data, ['upsert' => true]);
//                    StockPricing::raw()->insertOne($c_data);
                    $this->saveLog("saved data : ".json_encode($c_data), StockPricing::tableName());
                }
            }

            if (isset($stock_data["qty_data"])) {
                $qty_data = $stock_data["qty_data"];


                $qtyData = $qty_data["qty"];

                foreach ($qtyData as $c_data) {
                    $c_data["sku"] = $pro_data["sku"];
                    $update_data = array('$set' => $c_data);
                    $condition = array('sku' => strval($pro_data["sku"]), "storeid" => strval($c_data["storeid"]));
                    StockQty::raw()->updateOne($condition, $update_data, ['upsert' => true]);
//                    StockQty::raw()->insertOne($c_data);
                    $this->saveLog("saved data : ".json_encode($c_data), StockQty::tableName());
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
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getStock(Request $request): JsonResponse
    {
        try {
            $categoryTable = CategoryStock::tableName();
            $subCategoryTable = SubCategoryStock::tableName();

            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('sku' => $id)
                    )

                );
                $data = Stock::raw()->aggregate($ops)->toArray();
                $pricingData = StockPricing::raw()->aggregate($ops)->toArray();
                $qtyData = StockQty::raw()->aggregate($ops)->toArray();
                return response()->json([
                    'status' => 'success',
                    'data' => array(
                        'stock_data' => $data,
                        'pricing_data' => $pricingData,
                        'qty_data' => $qtyData
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
                $data = Stock::raw()->aggregate($ops)->toArray();
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
                $data = Stock::raw()->aggregate($ops)->toArray();
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function editStock(Request $request, $id): JsonResponse
    {
        try {
            if ($request->has('status')) {
                $update_data = array('$set' => array(
                    'status' => 'active'
                ));
                $condition = array('sku' => strval($id));
                Stock::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Stock::tableName());
            } else {
//        get product data and price data
                $all_data = $request->all();
                $pro_data = $all_data["stock_data"];

                $sku = $id;
                $price_data = $all_data["price_data"];
                $qty_data = $all_data["qty_data"];
                $update_data = array('$set' => $pro_data);
                $condition = array('sku' => strval($sku));
                Stock::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                $this->saveLog("sku: " . $sku . " updated data : " . json_encode($update_data), Stock::tableName());

                //        insert data to pricing table
                $prices_data = $price_data["prices"];

                foreach ($prices_data as $c_data) {
                    $update_data = array('$set' => $c_data);
                    $condition = array('sku' => strval($sku), "storeid" => strval($c_data["storeid"]));
                    StockPricing::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    $this->saveLog("sku: " . $sku .", storeid: " . $c_data["storeid"] . ", updated data : " . json_encode($update_data), StockPricing::tableName());
                }

                $qty_data = $qty_data["qty"];

                foreach ($qty_data as $c_data) {
                    $update_data = array('$set' => $c_data);
                    $condition = array('sku' => strval($sku), "storeid" => strval($c_data["storeid"]));
                    StockQty::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    $this->saveLog("sku: " . $sku .", storeid: " . $c_data["storeid"] . ", updated data : " . json_encode($update_data), StockQty::tableName());
                }

                $productData = Product::all();
                foreach($productData as $product){
                    $allocatedStock = $product["allocatedstock"];
                    if (count($allocatedStock) > 0){
                        $costData = array();
                        $location_data = Location::all();
                        foreach ($location_data as $location) {
                            $costData[$location->locationid] = 0;
                        }
                        foreach($allocatedStock as $stock){
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

                            if (count($stock_data) === 0) {
                                echo json_encode(array('status' => 'fail'));
                            } else {

                                switch ($stock_data[0]->stockoption) {
                                    case "each" :
                                        foreach ($stock_pricing_data as $stock_pricing) {
                                            if (array_key_exists($stock_pricing->storeid, $costData)) {
                                                $costData[$stock_pricing->storeid] += (float)$stock_pricing->stockprice * (float)$stock_qty;
                                            }
                                        }

                                        break;
                                    case "kgs":
                                    case "grams":
                                    case "mls" :
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
                        foreach ($costData as $key => $cost){
                            $update_data = array('$set' => array(
                                'productcost' => strval(number_format($cost, 2))
                            ));
                            $condition = array('sku' => $product["sku"], 'storeid' => strval($key));
                            Costing::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                            $this->saveLog("sku: " . $product["sku"] .", storeid: " . $key . ", updated data : " . json_encode($update_data), Costing::tableName());
                        }
                    }
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

    public function deleteStock($id): JsonResponse
    {
        try {

            $update_data = array('$set' => array('status' => 'inactive'));
            $condition = array('sku' => strval($id));
            Stock::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, Stock::tableName());
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

}
