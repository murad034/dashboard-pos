<?php

namespace App\Http\Controllers\Designer\OrderKeypad;

use App\Http\Controllers\Controller;
use App\Models\Fuction;
use App\Models\KeypadLayout;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BuilderController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $id = $request->get('id');
        $keypadList = KeypadLayout::all();
        return view('designer.order.builder', array('keypad_id' => $id, "keypad_layouts" => $keypadList));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function pushMultipleJson(Request $request): JsonResponse
    {
        try {
            $ids = $request->post('ids');
            $jdata = $request->post('jdata');
            $update_data = array('$set' => array(
                'jsonlayout' => $jdata
            ));
            $condition = array('layoutid' => array('$in' => $ids));
            KeypadLayout::raw()->updateMany($condition, $update_data);
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
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function delayOut($id): JsonResponse
    {
        try {
            KeypadLayout::raw()->deleteOne(array('layoutid' => strval($id)));
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cloneLayout(Request $request): JsonResponse
    {
        try {
            $id = $request->post('id');
            $cloneName = $request->post('clone_name');
            $keyid = $request->post('keyid');
            $tempDB = $this->gen_uid(15);
            // get update data
            $ops = array(

                array(
                    '$match' => array('layoutid' => $id)
                ),
                array(
                    '$out' => $tempDB
                )

            );
            $data = KeypadLayout::raw()->aggregate($ops)->toArray();
            // get max memberid
            $ops = array(

                array(
                    '$group' => array('_id' => null, 'maxid' => array('$max' => array(
                        '$toDouble' => '$layoutid'
                    )
                    ))
                )

            );
            $data = KeypadLayout::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $max_id = "0";
            } else {
                $max_id = $data[0]["maxid"];
            }
            $collection1 = DB::collection($tempDB);
            $condition = array();
            $update_data = array('$set' => array(
                'data' => $cloneName,
                'layoutid' => strval(++$max_id),
                'keypadid' => $keyid
            ));
            $collection1->raw()->updateMany($condition, $update_data);
            $ops = array();
            $temp_data = $collection1->raw()->aggregate($ops)->toArray();
            foreach ($temp_data as $data) {
                unset($data["_id"]);
                KeypadLayout::raw()->insertOne($data);
            }
            Schema::connection('mongodb')->dropIfExists($tempDB);
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

    public function gen_uid($l = 5)
    {
        return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 10, $l);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function addLayout(Request $request): JsonResponse
    {
        try {
            $IN = $request->post('input_name');
            $keyid = $request->post('keyid');

            $ops = array(

                array(
                    '$group' => array('_id' => null, 'maxid' => array('$max' =>
                        array(
                            '$toDouble' => '$keyid'
                        )))
                )

            );
            $data = KeypadLayout::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $data = array(
                    'keypadid' => $keyid,
                    'data' => $IN,
                    'jsonlayout' => '{"data":""}',
                    'layoutid' => "1"
                );
            } else {
                $max_id = $data[0]["maxid"];

                $data = array(
                    'keypadid' => $keyid,
                    'data' => $IN,
                    'jsonlayout' => '{"data":""}',
                    'layoutid' => strval(++$max_id)
                );

            }

            KeypadLayout::raw()->insertOne($data);
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
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function showProducts(): JsonResponse
    {
        try {
            $data = Product::all()->toArray();
            $html = "";
            foreach ($data as $obj) {
                $html .= "<a class='ui-draggable' data-ref= 'S-" . $obj["sku"] . "'>" . $obj["productname"] . "</a>";
            }
            return response()->json([
                'status' => 'success',
                'data' => $html
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
     * @return JsonResponse
     */
    public function showFunctions(): JsonResponse
    {
        try {
            $data = Fuction::all()->toArray();
            $html = "";
            foreach ($data as $obj) {
                $html .= "<a class='ui-draggable' data-ref= 'S-" . $obj['functionid'] . "'>" . $obj["functionname"] . "</a>";
            }
            return response()->json([
                'status' => 'success',
                'data' => $html
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
    public function pushJson(Request $request): JsonResponse
    {
        try {
            $id = $request->post('id');
            $jData = $request->post('data');
            $update_data = array(
                '$set' => array(
                'jsonlayout' => $jData
            ));

            $condition = array('layoutid' => strval($id));
            KeypadLayout::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            // get update data
            $ops = array(

                array(
                    '$match' => array('layoutid' => $id)
                )

            );
            $data = KeypadLayout::raw()->aggregate($ops)->toArray();
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
     * Store a draft resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function pushDraft(Request $request): JsonResponse
    {
        try {
            $id = $request->post('id');
            $jData = $request->post('data');
            $update_data = array('$set' => array(
                'draft' => $jData,
                'isdraft' => true,
                'scheduleAt' => null,
            ));

            $condition = array('layoutid' => strval($id));
            KeypadLayout::raw()->updateOne($condition, $update_data, ['upsert' => true]);
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
     * Store a draft resource schedule in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function pushDraftSchedule(Request $request): JsonResponse
    {
        try {
            $id = $request->post('id');
            $jData = $request->post('data');
            $scheduleTime = $request->post('scheduleTime');
            $update_data = array('$set' => array(
                'draft' => $jData,
                'isdraft' => true,
                'scheduleAt' => $scheduleTime,
            ));

            $condition = array('layoutid' => strval($id));
            KeypadLayout::raw()->updateOne($condition, $update_data, ['upsert' => true]);
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
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function pullJson($id): JsonResponse
    {
        try {
            $html = "";
            $ops = array(

                array(
                    '$match' => array(
                        "layoutid" => strval($id)
                    )
                )

            );
            $results = KeypadLayout::raw()->aggregate($ops)->toArray();

            foreach ($results as $obj) {
                $json = $obj->jsonlayout;
                $json = str_replace("'", "\"", $json);
                $obj = json_decode($json, true);
                $html .= $obj["data"];
            }
            return response()->json([
                'status' => 'success',
                'data' => $html
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
     * @return string
     */
    public function loadCloneLayoutList(): string
    {
        try {
            $html = "";
            $results = KeypadLayout::all()->toArray();
            foreach ($results as $obj) {
                $html .= "<a class='clone-list' id='" . $obj["layoutid"] . "'>" . $obj["data"] . "</a>";
            }
            return $html;

        } catch (Exception $e) {
            return "";
        }
    }

}
