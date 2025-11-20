<?php

namespace App\Helpers;

use App\Constants\ApiConstants;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image as Image;


class ApiHelper
{
    static function problemResponse($message = null, $status_code = null, $trace = null)
    {
        $code = !empty($status_code) ? $status_code : null;
        $traceMsg = empty($trace) ?  null  : $trace->getMessage();

        $body = [
            'message' => $message,
            'code' => $code,
            'success' => false,
            'error_debug' => $traceMsg,
        ];

        !empty($trace) ? logger($trace->getMessage(), $trace->getTrace()) : null;
        return response()->json($body)->setStatusCode($code);
    }


    /** Return error api response */
    static function inputErrorResponse($message = null, $status_code = null, $request = null, $trace = null)
    {
        $code = ($status_code != null) ? $status_code : '';
        $traceMsg = empty($trace) ?  null  : $trace->getMessage();
        $traceTrace = empty($trace) ?  null  : $trace->getTrace();

        $body = [
            'message' => $message,
            'code' => $code,
            'success' => false,
            'errors' => empty($trace) ?  null  : $trace->errors(),
        ];

        return response()->json($body)->setStatusCode($code);
    }

    /** Return valid api response */
    static function validResponse($message = null, $data = null, $request = null)
    {
        if (is_null($data) || empty($data)) {
            $data = null;
        }
        $body = [
            'message' => $message,
            'data' => $data,
            'success' => true,
            'code' => ApiConstants::GOOD_REQ_CODE,

        ];
        return response()->json($body);
    }

    /**Returns formatted date value
     * @param string date
     * @param string format
     */
    static function format_date($date, $format = 'Y-m-d')
    {
        return date($format, strtotime($date));
    }
}
