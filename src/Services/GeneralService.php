<?php

namespace Oscar\Massive\Services;

use Illuminate\Support\Facades\Log;
use Throwable;

class GeneralService
{

    function logDebug(Throwable $exception, array $extra = null)
    {
        try {
            Log::debug(
                $exception->getMessage(),
                [
                    'Trace' => "
                    'File' => {$exception->getTrace()[0]['file']},
                    'Line' => {$exception->getTrace()[0]['line']},
                    'Function' => {$exception->getTrace()[0]['function']},
                    'Class' => {$exception->getTrace()[0]['class']},
                    'Type' => {$exception->getTrace()[0]['type']}
                    ",
                    'extra' => $extra ?? 'El mejor CMS',
                ]
            );
        } catch (Throwable $exception) {
            Log::emergency('Hay problemas con los logs: ', [$exception->getMessage()]);
        }
    }

    public function capitalizeEntity(string $entity)
    {

        $arrEntity = explode('_', $entity);
        $entity = "";

        foreach ($arrEntity as $item) {
            $entity .= ucwords($item);
        }

        return $entity;
    }

    public function processResponse(string $message, $data = null)
    {

        $response['message'] = $message;

        if ($data) {
            $response['data'] = $data;
        }

        return $response;

    }

    public function removeItemsFromArray(&$array, $items)
    {

        foreach ($items as $item) {
            if (($key = array_search($item, $array)) !== false) {
                unset($array[$key]);
            }
        }

    }

}
