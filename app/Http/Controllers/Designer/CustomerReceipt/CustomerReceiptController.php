<?php

namespace App\Http\Controllers\Designer\CustomerReceipt;

use App\Http\Controllers\Controller;
use App\Models\ReceiptDesigner;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerReceiptController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('designer.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $receipt_data = $request->post('data');
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$templateid',
                            ))),
                ),

            );
            $data = ReceiptDesigner::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $receipt_data["templateid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $receipt_data["templateid"] = strval(++$max_id);
            }
            $receipt_data["isdraft"] = null;
            $receipt_data["scheduleAt"] = null;
            $receipt_data["status"] = "active";

            ReceiptDesigner::raw()->insertOne($receipt_data);

            $this->saveLog("saved data : ".json_encode($receipt_data), ReceiptDesigner::tableName());

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
                        '$match' => array('templateid' => $id),
                    ),

                );
                $data = ReceiptDesigner::raw()->aggregate($ops)->toArray();
            } else {
                $status_list = ['active', 'ACTIVE'];
                $ops = array(
                    array(
                        '$match' => array("status" => array('$in' => $status_list)),
                    ),
                );
                $data = ReceiptDesigner::raw()->aggregate($ops)->toArray();
            }
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function edit(Request $request, int $id): JsonResponse
    {
        try {
            $receipt_data = $request->all();
            $update_data = array('$set' => $receipt_data);
            $condition = array('templateid' => strval($id));
            ReceiptDesigner::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), ReceiptDesigner::tableName());
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $update_data = array('$set' => array('status' => 'inactive'));
            $condition = array('templateid' => strval($id));
            ReceiptDesigner::raw()->updateOne($condition, $update_data, ['upsert' => true]);

            $this->saveLog("deleted data (reference id): " . $id, ReceiptDesigner::tableName());
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
}
