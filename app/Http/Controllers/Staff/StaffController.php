<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('staff.index');
    }

    /**
     * convert object to array.
     *
     * @param $data
     * @return array
     */
    function object_to_array($data): array
    {
        if (is_array($data) || is_object($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = (is_array($data) || is_object($data)) ? $this->object_to_array($value) : $value;
            }
            return $result;
        }
        return $data;
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
            $image = $request->file('staffimage');

            $wb_image = url("/uploads/image");
            $destinationPath = public_path() . '/uploads/image/'; // make sure to create this directry in public_html (Apache, Cpanel) OR public (nginx)

            if ($image && $image->isValid()) {
                $fileExt = $image->extension();
                $fileName = md5(microtime());
                $image->move($destinationPath, $fileName . "." . $fileExt);
                $wb_image = $wb_image . '/' . $fileName . "." . $fileExt;
            } else {
                $wb_image = $wb_image . "/default-avatar.jpg";
            }
            $staff_data = json_decode($request->post('data'), true);
            if (isset($staff_data["c_id"])) {
                if ($image && $image->isValid()) {
                    $staff_data["c_data"]["staffimage"] = $wb_image;
                }

                $update_data = array('$set' => $staff_data["c_data"]);
                $condition = array('staffid' => strval($staff_data["c_id"]));
                Staff::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                $this->saveLog("reference id: " . $staff_data["c_id"] . " updated data : " . json_encode($update_data), Staff::tableName());
            } else {
//            insert data to table

                // get max memberid
                $ops = array(

                    array(
                        '$group' => array('_id' => null, 'maxid' => array('$max' => array(
                            '$toDouble' => '$staffid'
                        )))
                    )

                );
                $data = Staff::raw()->aggregate($ops)->toArray();
                if (count($data) === 0) {
                    $staff_data['staffid'] = "1";
                } else {
                    $max_id = $data[0]["maxid"];
                    $staff_data['staffid'] = strval(++$max_id);
                }

                $staff_data["status"] = "active";
                $staff_data["staffimage"] = $wb_image;


                Staff::raw()->insertOne($staff_data);
                $this->saveLog("saved data : ".json_encode($staff_data), Staff::tableName());
            }
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
                        '$match' => array('staffid' => $id)
                    )

                );
                $data = Staff::raw()->aggregate($ops)->toArray();
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

                $data = Staff::raw()->aggregate($ops)->toArray();
            } else {

                $data = Staff::all()->toArray();
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
            $staff_data = $request->all();
            $update_data = array('$set' => $staff_data);
            $condition = array('staffid' => strval($id));
            Staff::raw()->updateOne($condition, $update_data, ['upsert' => true]);

            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Staff::tableName());

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
            $condition = array('staffid' => strval($id));
            Staff::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, Staff::tableName());
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
