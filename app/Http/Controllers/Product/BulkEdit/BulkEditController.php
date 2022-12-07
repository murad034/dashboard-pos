<?php

namespace App\Http\Controllers\Product\BulkEdit;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Costing;
use App\Models\Location;
use App\Models\PriceDraft;
use App\Models\Pricing;
use App\Models\Product;
use App\Models\SubCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BulkEditController extends Controller
{


    public function index()
    {
        $locationList = Location::all();
        $categoryList = Category::all();
        return view('products.bulk.index', array(
            'locationList' => $locationList,
            'categoryList' => $categoryList,
        ));
    }

    public function object_to_array($data): array
    {
        if (is_array($data) || is_object($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = (is_array($data) || is_object($data)) ? $this->object_to_array($value) : $value;
            }
            return $result;
        }
        return $data;
    }

    /**
     * Display the specified resource.
     *
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function saveProductsDraft(Request $request): JsonResponse
    {
        try {
            $updateData = json_decode($request->post('data'), true);
            foreach ($updateData as $upData) {
                $update_data = array('$set' => array(
                    'productprice' => $upData["productprice"],
                    'producttier1' => $upData["producttier1"],
                    'producttier2' => $upData["producttier2"],
                    'producttier3' => $upData["producttier3"],
                    'producttier4' => $upData["producttier4"],
                    'producttier5' => $upData["producttier5"],
                ));
                $condition = array('sku' => $upData["sku"], 'storeid' => $upData["storeid"]);
                PriceDraft::raw()->updateOne($condition, $update_data, ['upsert' => true]);

                $this->saveLog("save Price Draft data: " . json_encode($upData), PriceDraft::tableName());
            }

            return response()->json([
                'status' => 'success',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
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

    public function saveProductsDraftSchedule(Request $request): JsonResponse
    {
        try {
            $updateData = json_decode($request->post('data'), true);
            $scheduleTime = $request->post('scheduleAt');
            foreach ($updateData as $upData) {
                $update_data = array('$set' => array(
                    'productprice' => $upData["productprice"],
                    'producttier1' => $upData["producttier1"],
                    'producttier2' => $upData["producttier2"],
                    'producttier3' => $upData["producttier3"],
                    'producttier4' => $upData["producttier4"],
                    'producttier5' => $upData["producttier5"],
                    'schedule_at' => $scheduleTime
                ));
                $condition = array('sku' => $upData["sku"], 'storeid' => $upData["storeid"]);
                PriceDraft::raw()->updateOne($condition, $update_data, ['upsert' => true]);

                $this->saveLog("save Price Draft data with schedule: " . json_encode($upData), PriceDraft::tableName());
            }

            return response()->json([
                'status' => 'success',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
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

    public function saveProducts(Request $request): JsonResponse
    {
        try {
            $updateData = json_decode($request->post('data'), true);
            foreach ($updateData as $upData) {
                $update_data = array('$set' => array(
                    'productprice' => $upData["productprice"],
                    'producttier1' => $upData["producttier1"],
                    'producttier2' => $upData["producttier2"],
                    'producttier3' => $upData["producttier3"],
                    'producttier4' => $upData["producttier4"],
                    'producttier5' => $upData["producttier5"],
                ));
                $condition = array('sku' => $upData["sku"], 'storeid' => $upData["storeid"]);
                Pricing::raw()->updateOne($condition, $update_data, ['upsert' => true]);

                $this->saveLog("updated data : " . json_encode($upData), Pricing::tableName());
            }

            return response()->json([
                'status' => 'success',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
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
    public function getProducts(Request $request): JsonResponse
    {
        try {
            $categoryTable = Category::tableName();
            $subCategoryTable = SubCategory::tableName();
            $productTable = Product::tableName();
            $costingTable = Costing::tableName();

            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('storeid' => strval($id)),
                    ),

                    array(
                        '$lookup' => array(
                            "from" => $productTable,
                            "localField" => "sku",
                            "foreignField" => "sku",
                            "as" => "product",
                        ),
                    ),
                    array(
                        '$unwind' => '$product',
                    ),

                    array(
                        '$lookup' => array(
                            "from" => $costingTable,
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
                                                        '$$storeid',
                                                    ),
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            "as" => "cost",
                        ),
                    ),
                    array(
                        '$unwind' => '$cost',
                    ),

                );
                $data = Pricing::raw()->aggregate($ops)->toArray();
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'data' => "put parameter id value",
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ]);
        }

    }

}
