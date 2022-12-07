<?php

namespace App\Http\Controllers\Designer\MediaBoard;

use App\Http\Controllers\Controller;
use App\Models\MenuBoard;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MediaBoardController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('designer.media.index');
    }

    /**
     * Display the specified resource.
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrderKeyPad(Request $request): JsonResponse
    {
        try {

            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('boardid' => $id)
                    )

                );
                $data = MenuBoard::raw()->aggregate($ops)->toArray();
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
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
                $data = MenuBoard::raw()->aggregate($ops)->toArray();
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
            } else {

                $data = MenuBoard::all()->toArray();
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
     * @return JsonResponse
     */
    public function saveOrderKeyPad(Request $request): JsonResponse
    {
        try {
            $board_data = $request->post('data');
            // get max memberid
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$boardid'
                            )))
                )

            );
            $data = MenuBoard::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $board_data["boardid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $board_data["boardid"] = strval(++$max_id);
            }

            $board_data["status"] = "active";
            $board_data["boardpassword"] = $this->generateRandomString(10);

            MenuBoard::raw()->insertOne($board_data);

            $this->saveLog("saved data : ".json_encode($board_data), MenuBoard::tableName());
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

    public function generateRandomString($length = 10): string
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function putOrderKeyPad(Request $request, int $id): JsonResponse
    {
        try {
            $board_data = $request->all();

            $update_data = array('$set' => $board_data);
            $condition = array('boardid' => strval($id));
            MenuBoard::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), MenuBoard::tableName());

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
    public function deleteOrderKeyPad(int $id): JsonResponse
    {
        try {
            $update_data = array('$set' => array('status' => 'inactive'));
            $condition = array('boardid' => strval($id));
            MenuBoard::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, MenuBoard::tableName());
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
