<?php

namespace App\Http\Controllers\Station\OrderMake;

use App\Http\Controllers\Controller;
use App\Models\OrderMakeStation;
use App\Models\ReceiptDesigner;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderMakeController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $designerList = ReceiptDesigner::all();
        return view('station.make.index', array('design_list' => $designerList));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $order_data = $request->post('data');
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$ordermakeid'
                            )))
                )

            );
            $data = OrderMakeStation::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $order_data["ordermakeid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $order_data["ordermakeid"] = strval(++$max_id);
            }

            $order_data["status"] = "active";

            OrderMakeStation::raw()->insertOne($order_data);

            $this->saveLog("saved data : ".json_encode($order_data), OrderMakeStation::tableName());

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
    public function show(Request $request): JsonResponse
    {
        try {
            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('ordermakeid' => $id)
                    )

                );
                $data = OrderMakeStation::raw()->aggregate($ops)->toArray();
            } else {
                $status_list = ['active', 'ACTIVE'];
                $ops = array(
                    array(
                        '$match' => array("status" => array('$in' => $status_list))
                    )
                );
                $data = OrderMakeStation::raw()->aggregate($ops)->toArray();
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


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $order_data = $request->all();
            $update_data = array('$set' => $order_data);
            $condition = array('ordermakeid' => strval($id));
            OrderMakeStation::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), OrderMakeStation::tableName());
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
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $update_data = array('$set' => array('status' => 'inactive'));
            $condition = array('ordermakeid' => strval($id));
            OrderMakeStation::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, OrderMakeStation::tableName());
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
