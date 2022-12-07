<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PosSale;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostmarkEmailController extends Controller
{
    public function webhookPostmarkInfoUpdate(Request $request): JsonResponse
    {

        try {
            $postmark_data = $request->post('data');
            $postmark_data["open_status"] = 1;

            PosSale::raw()->insertOne($postmark_data);
            $this->saveLog("saved data : ".json_encode($postmark_data), Quote::tableName());

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
