<?php

namespace App\Http\Controllers\Roster;

use App\Http\Controllers\Controller;
use App\Models\EjItem;
use App\Models\Location;
use App\Models\Roster;
use App\Models\RosterData;
use App\Models\Staff;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class BuilderController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        if ($request->has('locationid') && $request->has('startdate') && $request->has('rosterid')) {
            $location_id = $request->get('locationid');
            $start_date = $request->get('startdate');
            $roster_id = $request->get('rosterid');
            $ops = array(
                array(
                    '$match' => array("locationid" => $location_id)
                )
            );
            $locationList = Location::raw()->aggregate($ops)->toArray();
            if (count($locationList) > 0) {
                $staff_list = explode(",", $locationList[0]["allocatedstaff"]);
                $staff_list = array_map('trim', $staff_list);
                $ops = array(

                    array(
                        '$match' => array("staffid" => array('$in' => $staff_list))
                    ),
                    array(
                        '$project' => array(
                            '_id'   => 1,
                            'id'    => '$staffid',
                            'name'  => '$staffname',
                            'title' => '$department',
                            'img'   => '$staffimage',
                            'MO'    => '$availmonday',
                            'TU'    => '$availtuesday',
                            'WE'    => '$availwednesday',
                            'TH'    => '$availthursday',
                            'FR'    => '$availfriday',
                            'SA'    => '$availsaturday',
                            'SU'    => '$availsunday',
                        ),
                    )

                );
                $staffs = Staff::raw()->aggregate($ops)->toArray();
                $staffTotals = $this->getTotalHoursByStaff($staff_list, $start_date, $roster_id);
                foreach($staffs as $key => $staff){
                    $status = false;
                    foreach ($staffTotals as $sTotal){
                        if (strcmp($staff["id"], $sTotal["id"]) == 0){
                            $staffs[$key]["totalhours"] = $sTotal["totalHours"];
                            $status = true;
                        }
                    }
                    if ($status == false){
                        $staffs[$key]["totalhours"] = "0.00";
                    }
                }
                $roster_data = RosterData::all();
                return view('roster.builder', array('staffs' => $staffs, 'roster_data' => $roster_data, 'location_id' => $location_id, 'start_date' => $start_date, 'roster_id' => $roster_id));
            }
            else{
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


        } else {
            $locationList = Location::all();
            return view('roster.index', array('locations' => $locationList));
        }

    }

    public function getResources(Request $request): JsonResponse
    {
        try {
            if ($request->has('locationid') && $request->has('startdate') && $request->has('rosterid')) {
                $location_id = $request->get('locationid');
                $start_date = $request->get('startdate');
                $roster_id = $request->get('rosterid');
                $ops = array(
                    array(
                        '$match' => array("locationid" => $location_id)
                    )
                );
                $locationList = Location::raw()->aggregate($ops)->toArray();
                if (count($locationList) > 0) {
                    $staff_list = explode(",", $locationList[0]["allocatedstaff"]);
                    $staff_list = array_map('trim', $staff_list);
                    $ops = array(

                        array(
                            '$match' => array("staffid" => array('$in' => $staff_list))
                        ),
                        array(
                            '$project' => array('_id' => 1,
                                'id' => '$staffid',
                                'name' => '$staffname',
                                'title' => '$department',
                                'img' => '$staffimage')
                        )

                    );
                    $staffs = Staff::raw()->aggregate($ops)->toArray();
                    $staffTotals = $this->getTotalHoursByStaff($staff_list, $start_date, $roster_id);
                    foreach ($staffs as $key => $staff) {
                        $status = false;
                        foreach ($staffTotals as $sTotal) {
                            if (strcmp($staff["id"], $sTotal["id"]) == 0) {
                                $staffs[$key]["totalhours"] = $sTotal["totalHours"];
                                $status = true;
                            }
                        }
                        if ($status == false) {
                            $staffs[$key]["totalhours"] = "0.00";
                        }
                    }
                    return response()->json([
                        'status' => 'success',
                        'data' => $staffs
                    ]);
                } else {
                    $ops = array(
                        array(
                            '$sort' => array(
                                'staffid' => 1
                            )
                        )
                    );
                    $staffList = Staff::raw()->aggregate($ops)->toArray();
                    return response()->json([
                        'status' => 'success',
                        'data' => $staffList
                    ]);
                }
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

     public function getSingleStaffRosterPage(Request $request): JsonResponse
    {
        try {
            if ($request->has('num')) {
                $staffid = $request->get('num');
                $ops = array(
                    array(
                        '$match' => array("staffid" =>  $staffid)
                    )
                );
                $staffs = Staff::raw()->aggregate($ops)->toArray();
                return response()->json([
                    'status' => 'success',
                    'data' => $staffs[0]
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

    public function getTotalHoursByStaff($staff_list, $startDate, $rosterId)
    {

        $staffTable = Staff::tableName();
        $endDate = strtotime($startDate);
        $endDate = strtotime("+6 day", $endDate);
        $endDate = date('Y-m-d', $endDate);

        $ops = array(
            array(
                '$addFields' => array(
                    'startDate' => array(
                        '$dateFromString' => array(
                            'dateString' => '$start'
                        )
                    ),
                    'endDate' => array(
                        '$dateFromString' => array(
                            'dateString' => '$end'
                        )
                    ),
                )
            ),
            array(
                '$lookup' => array(
                    'from' => $staffTable,
                    'localField' => 'resource',
                    'foreignField' => 'staffid',
                    'as' => 'staff'
                )
            ),
            array(
                '$unwind' => '$staff'
            ),

            array(
                '$match' => array("resource" => array('$in' => $staff_list))
            ),
            array(
                '$addFields' => array(
                    'saleDate' => array(
                        '$substr' => array(
                            '$start', 0, 10
                        )
                    ),
                    'duration' => array(
                        '$divide' => array(
                            array(
                                '$subtract' => array(
                                    '$endDate', '$startDate'
                                )
                            ), 3600000
                        )
                    )
                )
            ),
            array(
                '$match' => array(
                    'saleDate' => array(
                        '$gte' => $startDate,
                        '$lte' => $endDate
                    ),
                    'slot' => array('$in' => ["1", 1]),
                    'rosterid' => strval($rosterId)
                )
            ),
            array(
                '$group' => array(
                    '_id' => '$resource',
                    'totalHours' => array(
                        '$sum' => array(
                            '$toDouble' => '$duration'
                        )
                    ),
                    'id' => array(
                        '$first' => '$resource'
                    )
                )
            ),
            array(
                '$sort' => array(
                    '_id' => 1
                )
            )
        );

        $staffHours = RosterData::raw()->aggregate($ops)->toArray();
        return $staffHours;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTable(Request $request): JsonResponse
    {
        try {
            if ($request->has('startdate') && $request->has('id')) {
                $staffTable = Staff::tableName();
                $startDate = $request->get('startdate');
                $rosterId = $request->get('id');
                $endDate = strtotime($startDate);
                $endDate = strtotime("+6 day", $endDate);
                $endDate = date('Y-m-d', $endDate);
                $result = [];
                $ops = array(

                    array(
                        '$addFields' => array(
                            'startDate' => array(
                                '$dateFromString' => array(
                                    'dateString' => '$start'
                                )
                            ),
                            'endDate' => array(
                                '$dateFromString' => array(
                                    'dateString' => '$end'
                                )
                            ),
                        )
                    ),
                    array(
                        '$lookup' => array(
                            'from' => $staffTable,
                            'localField' => 'resource',
                            'foreignField' => 'staffid',
                            'as' => 'staff'
                        )
                    ),
                    array(
                        '$unwind' => '$staff'
                    ),
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$substr' => array(
                                    '$start', 0, 10
                                )
                            ),
                            'duration' => array(
                                '$divide' => array(
                                    array(
                                        '$subtract' => array(
                                            '$endDate', '$startDate'
                                        )
                                    ), 3600000
                                )
                            )
                        )
                    ),
                    array(
                        '$addFields' => array(
                            'costVal' => array(
                                '$multiply' => array(
                                    array(
                                        '$toDouble' => '$duration'
                                    ),
                                    array(
                                        '$toDouble' => '$staff.payrateperhour'
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $startDate,
                                '$lte' => $endDate
                            ),
                            'slot' => array('$in' => ["1", 1]),
                            'rosterid' => strval($rosterId)
                        )
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$saleDate',
                            'saleDate' => array(
                                '$first' => '$saleDate'
                            ),
                            'totalHours' => array(
                                '$sum' => array(
                                    '$toDouble' => '$duration'
                                )
                            ),
                            'rosterCost' => array(
                                '$sum' => array(
                                    '$toDouble' => '$costVal'
                                )
                            )
                        )
                    ),
                    array(
                        '$sort' => array(
                            '_id' => 1
                        )
                    )

                );

                $temp = [];
                $temp1 = [];

                $data = RosterData::raw()->aggregate($ops)->toArray();

                // Ops meal break
                $ops_mb = array(

                    array(
                        '$addFields' => array(
                            'startDate' => array(
                                '$dateFromString' => array(
                                    'dateString' => '$start'
                                )
                            ),
                            'endDate' => array(
                                '$dateFromString' => array(
                                    'dateString' => '$end'
                                )
                            ),
                        )
                    ),
                    array(
                        '$lookup' => array(
                            'from' => $staffTable,
                            'localField' => 'resource',
                            'foreignField' => 'staffid',
                            'as' => 'staff'
                        )
                    ),
                    array(
                        '$unwind' => '$staff'
                    ),
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$substr' => array(
                                    '$start', 0, 10
                                )
                            ),
                            'duration' => array(
                                '$divide' => array(
                                    array(
                                        '$subtract' => array(
                                            '$endDate', '$startDate'
                                        )
                                    ), 3600000
                                )
                            ),
                        )
                    ),
                    array(
                        '$addFields' => array(
                            'costVal' => array(
                                '$multiply' => array(
                                    array(
                                        '$toDouble' => '$duration'
                                    ),
                                    array(
                                        '$toDouble' => '$staff.payrateperhour'
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $startDate,
                                '$lte' => $endDate
                            ),
                            'slot' => array('$in' => ["3"]),
                            'rosterid' => strval($rosterId)
                        )
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$saleDate',
                            'MealBreakstotalHours' => array(
                                '$sum' => array(
                                    '$toDouble' => '$duration'
                                ),
                            ),
                            'MealBreaksrosterCost' => array(
                                '$sum' => array(
                                    '$toDouble' => '$costVal'
                                )
                            )
                        )
                    ),
                    array(
                        '$sort' => array(
                            '_id' => 1
                        )
                    )

                );
                $data_mb = RosterData::raw()->aggregate($ops_mb)->toArray();
                $current = strtotime($startDate);
                $step = "+1 day";
                while ($current <= strtotime($endDate)) {
                    $status = false;
                    $mb=0;
                    foreach ($data as $obj) {
                        if ($obj->saleDate === date('Y-m-d', $current)) {
                            if(!empty($data_mb[$mb]->MealBreakstotalHours)){
                                $obj->totalHours = $obj->totalHours- $data_mb[$mb]->MealBreakstotalHours;
                            }
                            if(!empty($data_mb[$mb]->MealBreaksrosterCost)){
                                $obj->rosterCost = $obj->rosterCost- $data_mb[$mb]->MealBreaksrosterCost;
                            }
                            array_push($temp, $obj->totalHours);
                            array_push($temp1, $obj->rosterCost);
                            $status = true;
                        }
                        ++$mb;
                    }
                    if ($status === false) {
                        array_push($temp, 0);
                        array_push($temp1, 0);
                    }
                    $status = false;
                    $current = strtotime($step, $current);
                }

                $total = 0;
                foreach ($temp as $value) {
                    $total += (float)$value;
                }
                array_push($temp, $total);

                $total = 0;
                foreach ($temp1 as $value) {
                    $total += (float)$value;
                }
                array_push($temp1, $total);

                //        get Actual sales from roster table

                $ops = array(
                    array(
                        '$match' => array(
                            'rosterid' => strval($rosterId)
                        )
                    )
                );
                $temp3 = [];
                $data = Roster::raw()->aggregate($ops)->toArray();
                $current = strtotime($startDate);
                $step = "+1 day";
                $data = $data[0];
                while ($current <= strtotime($endDate)) {
                    $status = false;
                    foreach ($data as $key => $obj) {

                        if (strpos($key, strtolower(date('l', $current))) !== false) {
                            array_push($temp3, $obj);
                            $status = true;
                        }
                    }
                    if ($status === false) {
                        array_push($temp3, 0);
                    }
                    $status = false;
                    $current = strtotime($step, $current);
                }
                $total = 0;
                foreach ($temp3 as $value) {
                    $total += (float)$value;
                }
                array_push($temp3, $total);

                //      get Sales Budget , Actual Sales, Variance

                $ops = array(
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m-%d',
                                    'date' => '$datetime'
                                )
                            )
                        )
                    ),
                    array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $startDate,
                                '$lte' => $endDate
                            )
                        )
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$recnum',
                            'saleDate' => array(
                                '$first' => '$saleDate'
                            ),
                            'proVal' => array(
                                '$first' => '$provalue'
                            )
                        )
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$saleDate',
                            'saleBudget' => array(
                                '$sum' => array(
                                    '$toDouble' => '$proVal'
                                )
                            )
                        )
                    ),
                    array(
                        '$sort' => array(
                            '_id' => 1
                        )
                    )
                );
                $temp2 = [];
                $data = EjItem::raw()->aggregate($ops)->toArray();
                $current = strtotime($startDate);
                $step = "+1 day";
                while ($current <= strtotime($endDate)) {
                    $status = false;
                    foreach ($data as $obj) {
                        if ($obj->_id === date('Y-m-d', $current)) {
                            array_push($temp2, $obj->saleBudget);
                            $status = true;
                        }
                    }
                    if ($status === false) {
                        array_push($temp2, 0);
                    }
                    $status = false;
                    $current = strtotime($step, $current);
                }
                $total = 0;
                foreach ($temp2 as $value) {
                    $total += (float)$value;
                }
                array_push($temp2, $total);
                $temp4 = array_map(function ($x, $y) {
                    return floatval(str_replace(",", "", $x)) - floatval(str_replace(",", "", $y));
                }, $temp2, $temp3);


                $temp5 = array_map(
                    function ($x, $y, $z) {

                        if (floatval(str_replace(",", "", $y)) == 0 && floatval(str_replace(",", "", $z)) != 0) {

                            return floatval(str_replace(",", "", $x)) / floatval(str_replace(",", "", $z)) * 100;
                        } else if (floatval(str_replace(",", "", $y)) != 0) {
                            return floatval(str_replace(",", "", $x)) / floatval(str_replace(",", "", $y)) * 100;
                        } else {
                            return 0;
                        }
                    },
                    $temp1, $temp2, $temp3);
                foreach ($temp as $key => $value) {
                    if ($value === "NaN") {
                        $temp[$key] = "0.00";
                    } else {
                        $temp[$key] = "" . strval(number_format($value, 2));
                    }
                }
                foreach ($temp1 as $key => $value) {
                    if ($value === "NaN") {
                        $temp1[$key] = "$0.00";
                    } else {
                        $temp1[$key] = "$" . strval(number_format($value, 2));
                    }
                }
                foreach ($temp2 as $key => $value) {
                    if ($value === "NaN") {
                        $temp2[$key] = "$0.00";
                    } else {
                        $temp2[$key] = "$" . strval(number_format($value, 2));
                    }
                }
                foreach ($temp3 as $key => $value) {
                    if ($value === "NaN") {
                        $temp3[$key] = "$0.00";
                    } else {
                        $temp3[$key] = "$" . strval(number_format($value, 2));
                    }

                }
                foreach ($temp4 as $key => $value) {
                    if ($value === "NaN") {
                        $temp4[$key] = "$0.00";
                    } else {
                        $temp4[$key] = "$" . strval(number_format($value, 2));
                    }
                }
                foreach ($temp5 as $key => $value) {
                    if ($value === "NaN") {
                        $temp5[$key] = "$0.00";
                    } else {
                        $temp5[$key] = strval(number_format($value, 2)) . "%";
                    }

                }
                array_unshift($temp, "Total Hours");
                array_unshift($temp1, "Roster Cost");
                array_unshift($temp4, "Variance");
                array_unshift($temp3, "Sales Budget");
                array_unshift($temp2, "Actual Sales");
                array_unshift($temp5, "Cost%");
                array_push($result, $temp);
                array_push($result, $temp1);
                array_push($result, $temp3);
                array_push($result, $temp2);
                array_push($result, $temp4);
                array_push($result, $temp5);
                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'status' => 'fail'
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
    public function store(Request $request): JsonResponse
    {
        try {
            $roster_data = $request->post();
            $insertData = RosterData::raw()->insertOne($roster_data);
            if ($insertData){
                $ops = array(

                    array(
                        '$match' => array('_id' => $insertData->getInsertedId())
                    )

                );
                $data = RosterData::raw()->aggregate($ops)->toArray();
                $this->saveLog("saved data : ".json_encode($data), RosterData::tableName());
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
            }
            else{
                return response()->json([
                    'status' => 'fail',
                    'message' => "insert document invalid"
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $roster_data = $request->all();
            $update_data = array('$set' => $roster_data);
            $condition = array('_id' => new ObjectID($id));
            $updateResult = RosterData::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            // get update data
            $ops = array(

                array(
                    '$match' => array('_id' => new ObjectID($id))
                )

            );
            $data = RosterData::raw()->aggregate($ops)->toArray();

            $this->saveLog("updated data : " . json_encode($data), RosterData::tableName());

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
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            RosterData::raw()->deleteOne(['_id' => new ObjectID($id)]);
            $this->saveLog("deleted data (reference id): " . $id, RosterData::tableName());
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
