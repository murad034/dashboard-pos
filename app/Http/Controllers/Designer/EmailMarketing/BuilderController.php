<?php

namespace App\Http\Controllers\Designer\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
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
        return view('designer.email.builder', array('template_id' => $id));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getBuilder(int $id): JsonResponse
    {
        try {
            $ops = array(

                array(
                    '$match' => array('templateid' => strval($id))
                )

            );
            $data = EmailTemplate::raw()->aggregate($ops)->toArray();

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
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveBuilder(Request $request): JsonResponse
    {
        try {
            $template_data = $request->post('data');

            $update_data = array('$set' => $template_data["data"]);
            $condition = array('templateid' => strval($template_data["id"]));
            EmailTemplate::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("updated data : " . json_encode($update_data), EmailTemplate::tableName());
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
