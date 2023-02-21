<?php

namespace App\Core;

/**
 * Used to create service for used in controllers.
 */
abstract class CoreService
{

     /**
     * Provide the service response when success.
     *
     * @param mixed $output  the mixed type data that can convert to a json response
     * @param mixed $message
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonresSuccess($output, $message = '')
    {
        $outputArr['status'] = true;
        $outputArr['response_code'] = 200;
        $outputArr['response'] = $output;
        $outputArr['message'] = $message;

        return response()->json($outputArr);
    }

    /**
     * Provide the service response error.
     *
     * @param array $output  the mixed type data that can convert to a json response
     * @param mixed $message Additional message optional
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonresError(
        $output = ['error' => ['Bad request']],
        $message = ''
    ) {

        $outputArr['status'] = false;
        $outputArr['response_code'] = 400;
        $outputArr['response'] = $output;

        if (!empty($message)) {
            if (!empty($output)) {
                $output['message'] ??= ' '.$message;
            }
        }

        return response()->json($outputArr, 400);
    }

    /**
     * Provide the service response error.
     *
     * @param array $output  the mixed type data that can convert to a json response
     * @param mixed $message Additional message optional
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonresUnathorizedError(
        $output = ['error' => ['Unathorised']],
        $message = ''
    ) {

        $outputArr['status'] = false;
        $outputArr['response_code'] = 401;
        $outputArr['response'] = $output;

        if (!empty($message)) {
            if (!empty($output)) {
                $output['message'] ??= ' '.$message;
            }
        }

        return response()->json($outputArr, 401);
    }

    /**
     * Provide the service response error.
     *
     * @param array $output  the mixed type data that can convert to a json response
     * @param mixed $message Additional message optional
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonValidationError(
        $output = ['error' => ['Invalid Data']],
        $message = ''
    ){
        $outputArr['status'] = false;
        $outputArr['response_code'] = 422;
        $outputArr['response'] = $output;
        if (!empty($message)) {
            if (!empty($output)) {
                $output['message'] ??= ' '.$message;
            }
        }

        return response()->json($outputArr, 422);
    }

    /**
     * Provide the service reponse when there is a server error.
     *
     * @param mixed $ex    the exception that can be convert to a valid json response
     * @param mixed $extra
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonresServerError($ex, $extra = '')
    {
        $outputArr['status'] = false;
        $outputArr['response_code'] = 500;
        $outputArr['response'] = [
            'message' => 'Regret we are not able to process now, please try after some time.',
            'Exception' => $ex->getMessage(),
            'File' => $ex->getFile(),
            'Line No' => $ex->getLine(),
            'Handler' => get_class($ex),
            'extra' => $extra,
        ];
        return response()->json($outputArr, 500);
    }
}
