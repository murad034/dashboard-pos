<?php

namespace App\Http\Controllers\Roster;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Roster;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RosterController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $locationList = Location::all();
        return view('roster.index', array('locations' => $locationList));
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
            // get max rosterid
            $ops = array(

                array(
                    '$group' => array('_id' => null, 'maxid' => array('$max' => array(
                        '$toDouble' => '$rosterid'
                    )))
                )

            );
            $data = Roster::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $roster_data["rosterid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $roster_data["rosterid"] = strval(++$max_id);
            }

            Roster::raw()->insertOne($roster_data);
            $this->saveLog("saved data : ".json_encode($roster_data), Roster::tableName());
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
                        '$match' => array('rosterid' => $id)
                    )

                );
                $data = Roster::raw()->aggregate($ops)->toArray();
            } else if ($request->has('locationid')) {
                $id = $request->get('locationid');
                $ops = array(

                    array(
                        '$match' => array('locationid' => $id)
                    ),
                    array(
                        '$sort' => array(
                            'startdate' => -1
                        )
                    ),
                    array(
                        '$limit' => 1
                    )

                );
                $data = Roster::raw()->aggregate($ops)->toArray();
            } else {
                $ops = array(
                    array(
                        '$lookup' => array(
                            "from" => "locations",
                            "localField" => "locationid",
                            "foreignField" => "locationid",
                            "as" => "locations"
                        )
                    ),
                    array(
                        '$unwind' => '$locations'
                    )
                );
                $data = Roster::raw()->aggregate($ops)->toArray();
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
            $roster_data = $request->all();
            $update_data = array('$set' => $roster_data);
            $condition = array('rosterid' => strval($id));
            Roster::raw()->updateOne($condition, $update_data, ['upsert' => true]);

            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Roster::tableName());

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
