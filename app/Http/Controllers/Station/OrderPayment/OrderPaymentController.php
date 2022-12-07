<?php

namespace App\Http\Controllers\Station\OrderPayment;

use App\Http\Controllers\Controller;
use App\Models\Keypad;
use App\Models\KeypadLayout;
use App\Models\Location;
use App\Models\OrderMakeStation;
use App\Models\Terminal;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderPaymentController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $locations = Location::all();
        $orderList = OrderMakeStation::all();
        return view('station.payment.index', array('locations' => $locations, 'orderList' => $orderList));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTable(Request $request): JsonResponse
    {
        try {
            $locationName = Location::tableName();
            $keypadName = Keypad::tableName();
            $layoutName = KeypadLayout::tableName();
            $compareTime = new \MongoDB\BSON\UTCDateTime(time() * 1000 - 1000 * 15);
            $status_list = ['active', 'ACTIVE'];
            $ops = array(
                array(
                    '$match' => array("status" => array('$in' => $status_list))
                ),

                array(
                    '$lookup' => array(
                        "from" => $locationName,
                        "localField" => "locationid",
                        "foreignField" => "locationid",
                        "as" => "locations"
                    )
                ),
                array(
                    '$unwind' => '$locations'
                ),
                array(
                    '$lookup' => array(
                        "from" => $keypadName,
                        "localField" => "terminalkeypad",
                        "foreignField" => "keypadid",
                        "as" => "keypads"
                    )
                ),
                array(
                    '$unwind' => '$keypads'
                ),
                array(
                    '$lookup' => array(
                        "from" => $layoutName,
                        "localField" => "homelayout",
                        "foreignField" => "layoutid",
                        "as" => "layouts"
                    )
                ),
                array(
                    '$unwind' => '$layouts'
                ),
                array(
                    '$addFields' => array(
                        'online' => array(
                            '$cond' => array(
                                'if' => array(
                                    '$gte' => array(
                                        '$update_time',
                                        $compareTime
                                    )
                                ),
                                'then' => 'on',
                                'else' => 'off'
                            )
                        )
                    )
                )


            );
            $data = Terminal::raw()->aggregate($ops)->toArray();

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
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function terminalsPing(Request $request): JsonResponse
    {
        try {
            if ($request->has('id')) {
                $id = $request->post('id');

                $date_created = new \MongoDB\BSON\UTCDateTime(time() * 1000);
                $update_data = array('$set' => array(
                    'update_time' => $date_created
                ));
                $condition = array('terminalid' => strval($id));
                Terminal::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Terminal::tableName());
                return response()->json([
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'missed parameters'
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
    public function showKeyPad(Request $request): JsonResponse
    {
        try {
            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('keypadid' => $id)
                    )

                );
                $data = KeypadLayout::raw()->aggregate($ops)->toArray();
            } else {
                $data = Keypad::all()->toArray();
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
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $ops = array(

                array(
                    '$match' => array('terminalid' => $id)
                )

            );
            $data = Terminal::raw()->aggregate($ops)->toArray();
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        try {
            $terminal_data = $request->post('data');
            // get max memberid
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$terminalid'
                            )))
                )

            );
            $data = Terminal::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $terminal_data["terminalid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $terminal_data["terminalid"] = strval(++$max_id);
            }

            $terminal_data["status"] = "active";

            Terminal::raw()->insertOne($terminal_data);
            $this->saveLog("saved data : ".json_encode($terminal_data), Terminal::tableName());
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(Request $request, $id): JsonResponse
    {

        try {
            $data = $request->all();
            $update_data = array('$set' => $data);
            $condition = array('terminalid' => strval($id));
            Terminal::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Terminal::tableName());
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
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {

        try {
            $update_data = array('$set' => array('status' => 'inactive'));
            $condition = array('terminalid' => strval($id));
            Terminal::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, Terminal::tableName());
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
}
