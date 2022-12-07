<?php

namespace App\Http\Controllers\Station\MediaDisplay;

use App\Http\Controllers\Controller;
use App\Models\MediaDisplayStation;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MediaDisplayController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('station.media.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {

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
            $media_data = $request->post('data');
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$customkeyid'
                            )))
                )

            );
            $data = MediaDisplayStation::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $media_data["customkeyid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $media_data["customkeyid"] = strval(++$max_id);
            }

            $media_data["status"] = "active";

            MediaDisplayStation::raw()->insertOne($media_data);

            $this->saveLog("saved data : ".json_encode($media_data), MediaDisplayStation::tableName());

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
                        '$match' => array('customkeyid' => $id)
                    )

                );
                $data = MediaDisplayStation::raw()->aggregate($ops)->toArray();
            } else {
                $status_list = ['active', 'ACTIVE'];
                $ops = array(
                    array(
                        '$match' => array("status" => array('$in' => $status_list))
                    )
                );
                $data = MediaDisplayStation::raw()->aggregate($ops)->toArray();
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
            $media_data = $request->all();
            $update_data = array('$set' => $media_data);
            $condition = array('customkeyid' => strval($id));
            MediaDisplayStation::raw()->updateOne($condition, $update_data, ['upsert' => true]);

            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), MediaDisplayStation::tableName());

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
    public function destroy($id)
    {
        try {
            $update_data = array('$set' => array('status' => 'inactive'));
            $condition = array('customkeyid' => strval($id));
            MediaDisplayStation::raw()->updateOne($condition, $update_data, ['upsert' => true]);

            $this->saveLog("deleted data (reference id): " . $id, MediaDisplayStation::tableName());
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
