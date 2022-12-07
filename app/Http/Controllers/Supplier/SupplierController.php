<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Supplier;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $ops = array(
            array(
                '$sort' => array(
                    'staffid' => 1
                )
            )
        );
        $staffList = Staff::raw()->aggregate($ops);
        return view('supplier.index', array('staffs' => $staffList));
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
            $supplierData = $request->post();
            // get max memberid
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$supplier_id'
                            )))
                )

            );
            $data = Supplier::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $supplierData["supplier_id"] = "1";
                $supplierData["syncid"] = "LOC1";
            } else {
                $max_id = $data[0]["maxid"];
                $supplierData["supplier_id"] = strval(++$max_id);
                $supplierData["syncid"] = "LOC" . $supplierData["supplier_id"];
            }

            $supplierData["status"] = "active";
            Supplier::raw()->insertOne($supplierData);
            $this->saveLog("saved data : ".json_encode($supplierData), Supplier::tableName());
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
                        '$match' => array('supplier_id' => $id)
                    )

                );
                $data = Supplier::raw()->aggregate($ops)->toArray();
            } else if ($request->has('status')) {
                $show_status = $_GET['status'];
                if (strcmp($show_status, "all") == 0) {
                    $ops = array();
                } else {
                    $ops = array(

                        array(
                            '$match' => array('status' => $show_status)
                        )

                    );
                }
                $data = Supplier::raw()->aggregate($ops)->toArray();
            } else {
                $data = Supplier::all()->toArray();
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
            $promo_data = $request->all();
            $update_data = array('$set' => $promo_data);
            $condition = array('supplier_id' => strval($id));
            Supplier::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Supplier::tableName());

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
            $condition = array('supplier_id' => strval($id));
            Supplier::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, Supplier::tableName());
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
