<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function response_success($message, $data, $key)
    {
        http_response_code(200);
        if( !empty($data) )
            echo json_encode( ['success' => true, 'message' => '', $key => $data] );
        else
            echo json_encode( ['success' => true, 'message' => $message]);

        exit;
    }

    public function response_error($message, $status)
    {
        http_response_code($status);
        echo json_encode( ['success' => false, 'message' => $message] );
        exit;
    }

}
