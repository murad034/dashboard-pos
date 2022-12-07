<?php

namespace App\Http\Controllers\Designer\OrderKeypad;

use App\Http\Controllers\Controller;
use App\Models\Keypad;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderKeypadController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $keypads = Keypad::all();
        return view('designer.order.index', array('keypads' => $keypads));
    }

    /**
     * /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function getKeyPad($id): JsonResponse
    {
        try {
            $ops = array(

                array(
                    '$match' => array('keypadid' => $id)
                )

            );
            $data = Keypad::raw()->aggregate($ops)->toArray();
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
     * /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function getAllKeyPad(): JsonResponse
    {
        try {
            $data = Keypad::all()->toArray();
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
     * /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function editKeyPad(Request $request, $id): JsonResponse
    {
        try {
            $up_data = $request->all();
            $update_data = array('$set' => $up_data);
            $condition = array('keypadid' => strval($id));
            Keypad::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Keypad::tableName());
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
     * /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveKeyPad(Request $request): JsonResponse
    {
        try {
            $keypad_data = $request->post('data');
            // get max memberid
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$keypadid'
                            )))
                )

            );
            $data = Keypad::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $keypad_data["keypadid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $keypad_data["keypadid"] = strval(++$max_id);
            }


            Keypad::raw()->insertOne($keypad_data);
            $this->saveLog("saved data : ".json_encode($keypad_data), Keypad::tableName());
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
