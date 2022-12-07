<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function flashMessage($icon, $message, $alert)
    {
        Session::flash('flash_message', [
            'msg' => "<i class='fa fa-fw fa-$icon'></i> $message",
            'class' => "alert-$alert"
        ]);
    }

    /**
     * save user operation to log table
     * @param $message
     * @param $table
     * @return JsonResponse
     */

    public function saveLog($message, $table): \Illuminate\Http\JsonResponse
    {
        try {
            if (Auth::check()) {
                $log_data = array(
                    'user_id' => Auth::user()->user_id,
                    'user_name' => Auth::user()->name,
                    'action' => $message,
                    'table' => $table
                );
                Log::Create($log_data);
                return response()->json([
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => "aut check failed"
                ]);
            }


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
}
