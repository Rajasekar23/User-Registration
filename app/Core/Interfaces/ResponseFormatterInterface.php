<?php

namespace App\Core\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

/**
 * Services.
 */
interface ResponseFormatterInterface
{
    /**
     * Used to get the current logged in user.
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function getUser(): User|null;

    /**
     * Response to Client.
     *
     * @param mixed $response Anr formatted response
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonResponseToClient($response): JsonResponse|array|Collection|Model;

    /**
     * Provide the service response when success.
     *
     * @param mixed $output  the mixed type data that can convert to a json response
     * @param mixed $message
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonresSuccess($output, $message = ''): JsonResponse|array|Collection|Model;

    /**
     * Provide the service response error.
     *
     * @param array $output  the mixed type data that can convert to a json response
     * @param mixed $message Additional message optional
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonresError($output = ['error' => ['Bad request']], $message = ''): JsonResponse|array|Collection;

    /**
     * Provide the service response error.
     *
     * @param array $output  the mixed type data that can convert to a json response
     * @param mixed $message Additional message optional
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonresUnathorizedError($output = ['error' => ['Bad request']], $message = ''): JsonResponse|array|Collection;

    /**
     * Provide the service response error.
     *
     * @param array $output  the mixed type data that can convert to a json response
     * @param mixed $message Additional message optional
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonValidationError($output = ['error' => ['Invalid Data']], $message = ''): JsonResponse|array|Collection;

    /**
     * Provide the service reponse when there is a server error.
     *
     * @param mixed $ex    the exception that can be convert to a valid json response
     * @param mixed $extra
     *
     * @return \Illuminate\Http\JsonResponse json response
     */
    public function jsonresServerError($ex, $extra = ''): JsonResponse|array|Collection;
}
