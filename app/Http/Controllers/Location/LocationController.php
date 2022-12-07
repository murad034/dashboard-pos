<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Staff;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
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
        return view('location.index', array('staffs' => $staffList));
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
            $image = $request->file('image');
            $lt_image = url("/uploads/image/location");
            $destinationPath = public_path() . '/uploads/image/location/';
            if ($image) {
                $fileExt = $image->extension();
                $fileName = md5(microtime());
                $image->move($destinationPath, $fileName . "." . $fileExt);
                $lt_image = $lt_image . '/' . $fileName . "." . $fileExt;
            }
            $locationData = json_decode($request->post('data'), true);
            // get max memberid
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$locationid'
                            )))
                )

            );
            $data = Location::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $locationData["locationid"] = "1";
                $locationData["syncid"] = "LOC1";
            } else {
                $max_id = $data[0]["maxid"];
                $locationData["locationid"] = strval(++$max_id);
                $locationData["syncid"] = "LOC" . $locationData["locationid"];
            }
            $locationData["image"] = $lt_image;
            $locationData["status"] = "active";
            Location::raw()->insertOne($locationData);
            $this->saveLog("saved data : ".json_encode($locationData), Location::tableName());
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
                        '$match' => array('locationid' => $id)
                    )

                );
                $data = Location::raw()->aggregate($ops)->toArray();
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
                $data = Location::raw()->aggregate($ops)->toArray();
                foreach ($data as $key => $obj) {
                    $staffs = $obj["allocatedstaff"];
                    $staff_list = explode(",", $staffs);

                    $ops = array(

                        array(
                            '$match' => array("staffid" => array('$in' => $staff_list))
                        )

                    );
                    $staffLists = Staff::raw()->aggregate($ops)->toArray();


                    $staff_result = [];
                    foreach ($staffLists as $staff) {
                        $staff_arr = [];
                        array_push($staff_arr, $staff->staffname);
                        array_push($staff_arr, $staff->staffimage);
                        array_push($staff_result, $staff_arr);
                    }
                    $data[$key]["staffLists"] = json_encode($staff_result);
                }
            } else {
                $data = Location::all()->toArray();
                foreach ($data as $key => $obj) {
                    $staffs = $obj["allocatedstaff"];
                    $staff_list = explode(",", $staffs);
                    $ops = array(

                        array(
                            '$match' => array("staffid" => array('$in' => $staff_list))
                        )

                    );
                    $staffLists = Staff::raw()->aggregate($ops)->toArray();


                    $staff_result = [];
                    foreach ($staffLists as $staff) {
                        $staff_arr = [];
                        array_push($staff_arr, $staff->staffname);
                        array_push($staff_arr, $staff->staffimage);
                        array_push($staff_result, $staff_arr);
                    }
                    $data[$key]["staffLists"] = json_encode($staff_result);
                }
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
            $image = $request->file('image');
            $lt_image = url("/uploads/image/location");
            $destinationPath = public_path() . '/uploads/image/location/';
            if ($image) {
                $fileExt = $image->extension();
                $fileName = md5(microtime());
                $image->move($destinationPath, $fileName . "." . $fileExt);
                $lt_image = $lt_image . '/' . $fileName . "." . $fileExt;
            }
            $promo_data = json_decode($request->post('data'), true);
            $promo_data["image"] = $lt_image;
            $update_data = array('$set' => $promo_data);
            $condition = array('locationid' => strval($id));
            Location::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Location::tableName());

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
            $condition = array('locationid' => strval($id));
            Location::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, Location::tableName());
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
