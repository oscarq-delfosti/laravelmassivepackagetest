<?php

namespace Oscar\Massive\Traits;

use Carbon\Carbon;
use Error;
use Illuminate\Http\JsonResponse;

trait HasResponse
{
    /**
     * Default structure to prepare any json response
     *
     * @param string $message
     * @param int $code
     * @return array
     */
    private function defaultStructure($code = JsonResponse::HTTP_OK, $message = 'OK')
    {
        return [
            'status' => [
                'code' => $code,
                'message' => $message,
            ],
            'timestamp' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function defaultResponse($message = 'OK', $code = JsonResponse::HTTP_NO_CONTENT)
    {
        $structure = $this->defaultStructure($code, $message);

        return response()->json($structure, $code);
    }

    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $message = 'OK', $code = JsonResponse::HTTP_OK)
    {
        $structure = $this->defaultStructure($code, $message);
        $structure['data'] = $data;

        return response()->json($structure, $code);
    }

    /**
     * @param $errors
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($errors, $message, $code)
    {
        $errorsIsArray = is_array($errors);

        $structure = $this->defaultStructure($code, $message);
        $structure['errors'] = !$errorsIsArray || ($errorsIsArray && count($errors) > 0) ? $errors : null;

        return response()->json($structure, $code);
    }

    public function exceptionResponse(Error $error)
    {
        $structure = $this->defaultStructure(
            $error->getCode(),
            $error->getMessage()
        );

        return response()->json($structure, $error->getCode());
    }

    public function validationErrorResponse($errors)
    {
        $structure = [];
        $structure["message"] = "The given data was invalid.";
        $structure["errors"] = $errors;

        return response()->json($structure, 422);
    }
}
