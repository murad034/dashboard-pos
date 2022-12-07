<?php

namespace App\Http\Controllers\Designer\CustomerReceipt;

use App\Http\Controllers\Controller;
use App\Models\ReceiptDesigner;
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
        $id = $request->get('templateid');
        return view('designer.customer.builder', array('template_id' => $id));
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function getBuilder($id): JsonResponse
    {
        try {
            $ops = array(

                array(
                    '$match' => array('templateid' => strval($id)),
                ),

            );
            $data = ReceiptDesigner::raw()->aggregate($ops)->toArray();

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
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
            $template_data = $request->post('data');

            ReceiptDesigner::saveLive($template_data);

            $this->saveLog(" updated data : " . json_encode($template_data), ReceiptDesigner::tableName());

            return response()->json([
                'status' => 'success',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function saveDraftBuilder(Request $request): JsonResponse
    {
        try {
            $template_data = $request->post('data');
            ReceiptDesigner::saveDraft($template_data);

            $this->saveLog("saved draft : " . json_encode($template_data), ReceiptDesigner::tableName());

            return response()->json([
                'status' => 'success',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function saveDraftWithScheduleAtBuilder(Request $request): JsonResponse
    {
        try {
            $template_data = $request->post('data');
            if ($template_data['scheduleAt']) {
                ReceiptDesigner::saveDraftWithScheduleAt($template_data);
                $this->saveLog("saved draft with schedule : " . json_encode($template_data), ReceiptDesigner::tableName());
                return response()->json([
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => "Please set schedule time",
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
