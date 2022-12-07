<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\EjItem;
use App\Models\EjUnique;
use App\Models\Tag;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $status_list = ['active', 'ACTIVE'];
        $ops = array(
            array(
                '$match' => array("status" => array('$in' => $status_list))
            ),

        );
        $tagList = Tag::raw()->aggregate($ops);
        return view('customer.index', array('tags' => $tagList));
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
            $customer_data = $request->post('data');
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$customerid'
                            )),
                        'maxmemid' =>
                            array(
                                '$max' =>
                                    array(
                                        '$toDouble' => '$memberid'
                                    )))
                )

            );
            $data = Customer::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $customer_data["memberid"] = "0000001";
                $customer_data["customerid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $maxmem_id = $data[0]["maxmemid"];
                $customer_data["memberid"] = str_pad((string)++$maxmem_id, 7, "0", STR_PAD_LEFT);
                $customer_data["customerid"] = strval(++$max_id);
            }
            $customer_data["customername"] = $customer_data["customerfirstname"] . " " . $customer_data["customerlastname"];
            $customer_data["regdate"] = new UTCDateTime(time() * 1000);
            $customer_data["status"] = "active";

            Customer::raw()->insertOne($customer_data);
            $this->saveLog("saved data : ".json_encode($customer_data), Customer::tableName());

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
            $total_sales_sum_by_customerid =(float) 0;
            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('customerid' => $id)
                    )

                );
                $data = Customer::raw()->aggregate($ops)->toArray();
                $ops_customer_sale = array(
                    array(
                        '$match' => array('customerid' => $id)
                    )
                );
                $total_sales = EjUnique::raw()->aggregate($ops_customer_sale)->toArray();
                if(!empty($total_sales)){
                    foreach ($total_sales as $sale_data){
                        if(!empty($sale_data->saletotal)){
                            $total_sales_sum_by_customerid += (float) $sale_data->saletotal;
                        }
                    }
                }

            } else if ($request->has('status')) {
                $show_status = $request->get('status');
                if (strcmp($show_status, "all") == 0) {
                    $ops = array();
                } else {
                    $ops = array(

                        array(
                            '$match' => array('status' => $show_status)
                        )

                    );
                }

                $data = Customer::raw()->aggregate($ops)->toArray();
            } else {

                $data = Customer::all()->toArray();
            }
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'total_sales_by_customerid' => $total_sales_sum_by_customerid
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
    public function showHistory(Request $request): JsonResponse
    {
        try {
            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('customerid' => $id)
                    )

                );
                $data = EjUnique::raw()->aggregate($ops)->toArray();
            } else {

                $data = EjUnique::all()->toArray();
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
            $customer_data = $request->all();
            if ($request->has('customerfirstname') && $request->has('customerfirstname')) {
                $customer_data["customername"] = $customer_data["customerfirstname"] . " " . $customer_data["customerlastname"];
            }
            $update_data = array('$set' => $customer_data);
            $condition = array('customerid' => strval($id));
            Customer::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Customer::tableName());
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
            $update_data = array('$set' => array('status' => 'inactive', 'allocatedtags' => ''));
            $condition = array('customerid' => strval($id));
            Customer::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, Customer::tableName());
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
