<?php namespace Judge\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Contracts\ArrayableInterface;

class ApiController extends BaseController
{
    /**
     * Formats an array in JSEND format
     *
     * @param array $data The data to send to the user
     * @param bool $success If the response is a success
     * @param int $code The HTTP status code of the response. Defaults to 200
     * @param string $message The message to send to the user
     *
     * @return Response
     */
    public static function formatJSend($data = array(), $success = true, $code = 200, $message = '')
    {
        // Convert the data to an array if not an instance
        if ($data instanceof ArrayableInterface) {
            $data = $data->toArray();
        }

        return Response::json(array(
            'status' => $success ? 'success' : 'error',
            'code' => $code,
            'message' => $message,
            'data' => $data
        ));
    }
}
