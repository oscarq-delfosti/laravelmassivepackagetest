<?php

namespace Oscar\Massive\Services;

use Throwable;

class ModelService
{

    private const PATH = '\App\Models\\';

    private $generalService;

    public function __construct()
    {
        $this->generalService = new GeneralService();
    }

    public function getModel(string $modelName, string $site = null)
    {
        try {
            $model = self::getPath($modelName, $site);
            return new $model;
        } catch (Throwable $exception) {
            $this->generalService->logDebug($exception);
            return null;
        }
    }

    public function getPath(string $modelName, string $site = null)
    {
        try {
            if ($site) {
                return self::PATH . $site . '\\' . $modelName;
            } else {
                return self::PATH . $modelName;
            }
        } catch (Throwable $exception) {
            $this->generalService->logDebug($exception);
            return null;
        }
    }

    public function getFields(string $modelName, string $site = null)
    {
        $model = $this->getModel($modelName, $site);
        $fields = $model->getFillable();
        return $fields;
    }

    public function filterFieldsByFillable(&$fields, $fillable)
    {
        foreach ($fields as $key => $field) {
            if (!in_array($key, $fillable)) {
                unset($fields[$key]);
            }
        }
    }

}
