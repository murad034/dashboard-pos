<?php

namespace App\Http\Controllers\Marketing\Automation;

use App\Http\Controllers\Controller;
use App\Models\Automation;
use App\Models\Location;
use App\Models\Tag;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuilderController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $id = $request->get('automationid');
        return view('automation.builder', array('automation_id' => $id));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getBuilder(Request $request): JsonResponse
    {
        try {

            $id = $request->get('id');
            $ops = array(

                array(
                    '$match' => array('automationid' => $id)
                )

            );
            $data = Automation::raw()->aggregate($ops)->toArray();
            $tagData = Tag::all()->toArray();
            $locationData = Location::all()->toArray();
            return response()->json([
                'status' => 'success',
                'data' => array(
                    'tags' => $tagData,
                    'stores' => $locationData,
                    'data' => $data
                )
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
    public function saveBuilder(Request $request): JsonResponse
    {
        try {
            $template_data = $request->post();

            $update_data = array('$set' => array(
                'automationdata' => json_decode($template_data["data"])
            ));
            $condition = array('automationid' => strval($template_data["id"]));
            Automation::raw()->updateOne($condition, $update_data, ['upsert' => true]);

            $this->saveLog("updated data : " . json_encode($update_data), Automation::tableName());
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
