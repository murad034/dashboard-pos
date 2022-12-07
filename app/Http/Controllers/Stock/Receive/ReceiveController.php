<?php

namespace App\Http\Controllers\Stock\Receive;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Stock;
use App\Models\StockLog;
use App\Models\StockQty;
use App\Models\StockUsed;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReceiveController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('stocks.receive.index');
    }

    /**
     *  save data
     *
     * @return JsonResponse
     */
    public function saveReceiveStock(Request $request): JsonResponse
    {
        try {
            $stock_all = $request->post('data');

            $stockqty_data = $stock_all["stockqty"];
            $stockinlog_data = $stock_all["stockinlog"];
            $storeid = $stock_all["storeid"];

            foreach ($stockqty_data as $qty_data) {
                $ops = array(
                    array(
                        '$match' => array('sku' => strval($qty_data["sku"]), "storeid" => strval($storeid))
                    )
                );

                $data = StockQty::raw()->aggregate($ops)->toArray();

                if (count($data) !== 0) {
                    $current_qty = (float)$data[0]["stockqty"];
                    $qty_data["stockqty"] = strval(number_format((float)$qty_data["stockqty"] + $current_qty, 2));
                    $qty_data["syncid"] = "LOC" . $storeid;
                    $update_data = array('$set' => $qty_data);
                    $condition = array('sku' => strval($qty_data["sku"]), "storeid" => strval($storeid));
                    StockQty::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    $this->saveLog("sky: " . $qty_data["sku"] .", storeid: " . $storeid . ", updated data : " . json_encode($update_data), StockQty::tableName());
                } else {
                    $qty_data["syncid"] = "LOC" . $storeid;
                    $update_data = array('$set' => $qty_data);
                    $condition = array('sku' => strval($qty_data->sku), "storeid" => strval($storeid));
                    StockQty::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    $this->saveLog("sky: " . $qty_data->sku .", storeid: " . $storeid . ", updated data : " . json_encode($update_data), StockQty::tableName());
                }
                // New Column with date picker and save to DB on a new table for stockubd
                $saveData = array(
                    "storeid" => $storeid,
                    "usedbydate" => $qty_data["usedbydate"],
                    "datereceived" => $stockinlog_data["datereceived"],
                    "sku" => $qty_data["sku"],
                );
                StockUsed::raw()->insertOne($saveData);
                $this->saveLog("saved data : ".json_encode($saveData), StockUsed::tableName());
            }
            StockLog::raw()->insertOne($stockinlog_data);
            $this->saveLog("saved data : ".json_encode($stockinlog_data), StockLog::tableName());
            return response()->json([
                'status' => 'success',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }


    }

    /**
     *  get data
     *
     * @return JsonResponse
     */
    public function getLocation(Request $request): JsonResponse
    {
        try {
            $data = Location::all()->toArray();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     *  get data
     *
     * @return JsonResponse
     */
    public function getReceiveStock(Request $request): JsonResponse
    {
        $stockName = Stock::tableName();
        try {
            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(
                    array(
                        '$lookup' => array(
                            "from" => $stockName,
                            "localField" => "sku",
                            "foreignField" => "sku",
                            "as" => "stock"
                        )
                    ),
                    array(
                        '$unwind' => '$stock'
                    ),
                    array(
                        '$match' => array('storeid' => strval($id))
                    )
                );
                $data = StockQty::raw()->aggregate($ops)->toArray();
            } else {
                $ops = array(
                    array(
                        '$lookup' => array(
                            "from" => $stockName,
                            "localField" => "sku",
                            "foreignField" => "sku",
                            "as" => "stock"
                        )
                    ),
                    array(
                        '$unwind' => '$stock'
                    )
                );
                $data = StockQty::raw()->aggregate($ops)->toArray();
            }

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

}
