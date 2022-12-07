<?php

namespace App\Http\Controllers\Promo;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('promo.index');
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
            $promo_data = $request->post('data');
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$promosid'
                            )))
                )

            );
            $data = Promo::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $promo_data["promosid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $promo_data["promosid"] = strval(++$max_id);
            }

            $promo_data["status"] = "active";

            Promo::raw()->insertOne($promo_data);
            $this->saveLog("saved data : ".json_encode($promo_data), Promo::tableName());
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
                        '$match' => array('promosid' => $id)
                    )

                );
                $data = Promo::raw()->aggregate($ops)->toArray();
            } else {
                $status_list = ['active', 'ACTIVE'];
                $ops = array(
                    array(
                        '$match' => array("status" => array('$in' => $status_list))
                    )
                );
                $data = Promo::raw()->aggregate($ops)->toArray();
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
            $condition = array('promosid' => strval($id));
            Promo::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Promo::tableName());
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
            $condition = array('promosid' => strval($id));
            Promo::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, Promo::tableName());
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
