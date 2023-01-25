<?php

namespace Oscar\Massive\Services;

use ErrorException;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class MassiveService
{

    private $generalService;
    private $modelService;

    public function __construct()
    {
        $this->generalService = new GeneralService();
        $this->modelService = new ModelService();
    }

    public function create($args)
    {

        $entity = $this->generalService->capitalizeEntity($args['entity']);
        $items = $args['items'];

        $itemsSaved = [];
        $itemsInvalid = [];
        $itemsFailed = [];

        $model = $this->modelService->getModel($entity);

        if (!$model) {
            return $this->generalService->processResponse("The entity does not exist");
        }

        $fields = $this->modelService->getFields($entity);

        $fieldsToRemove = [
            'created_at',
            'updated_at'
        ];

        $this->generalService->removeItemsFromArray($fields, $fieldsToRemove);

        foreach ($items as $item) {

            $validate = Validator::make($item, $model->validations['create']);

            // Items that failed validation
            if ($validate->fails()) {

                $data = [];
                $data['item'] = $item;
                $data['errors'] = $validate->errors();

                array_push($itemsInvalid, $data);

            } else {

                try {

                    $this->modelService->filterFieldsByFillable($item, $fields);

                    $model::create($item);

                    array_push($itemsSaved, $item);

                } catch (ErrorException $ex) {

                    $data = [];
                    $data['item'] = $item;
                    $data['error']['code'] = $ex->getCode();
                    $data['error']['message'] = $ex->getMessage();

                    array_push($itemsFailed, $data);

                } catch (QueryException $ex) {

                    $data = [];
                    $data['item'] = $item;
                    $data['error']['code'] = $ex->getCode();
                    $data['error']['message'] = $ex->getPrevious()->getMessage();

                    array_push($itemsFailed, $data);
                }

            }
        }

        $data = [
            'saved' => $itemsSaved,
            'invalid' => $itemsInvalid,
            'failed' => $itemsFailed,
        ];

        $message = "";

        if (count($itemsSaved) == 0) {
            $message = "No item has been registered";
        } else if (count($itemsSaved) > 0 && (count($itemsInvalid) > 0 || count($itemsFailed) > 0)) {
            $message = "Some items have not been registered";
        } else if (count($itemsSaved) > 0 && (count($itemsInvalid) == 0 || count($itemsFailed) == 0)) {
            $message = "All items registered";
        }

        return $this->generalService->processResponse($message, $data);

    }

    public function update($args)
    {

        $entity = $this->generalService->capitalizeEntity($args['entity']);
        $items = $args['items'];

        $itemsUpdated = [];
        $itemsInvalid = [];
        $itemsFailed = [];

        $model = $this->modelService->getModel($entity);

        if (!$model) {
            return $this->generalService->processResponse("The entity does not exist");
        }

        $fields = $this->modelService->getFields($entity);

        $fieldsToRemove = [
            'created_at',
            'updated_at'
        ];

        $this->generalService->removeItemsFromArray($fields, $fieldsToRemove);

        foreach ($items as $item) {

            $validate = Validator::make($item, $model->validations['update']);

            // Items that failed validation
            if ($validate->fails()) {

                $data = [];
                $data['item'] = $item;
                $data['errors'] = $validate->errors();

                array_push($itemsInvalid, $data);

            } else {

                try {

                    $itemToUpdate = $model::find($item['id']);

                    $this->modelService->filterFieldsByFillable($item, $fields);

                    foreach ($item as $key => $it) {
                        $itemToUpdate->$key = $item[$key];
                    }

                    $itemToUpdate->save();

                    array_push($itemsUpdated, $item);

                } catch (ErrorException $ex) {

                    $data = [];
                    $data['item'] = $item;
                    $data['error']['code'] = $ex->getCode();
                    $data['error']['message'] = $ex->getMessage();

                    array_push($itemsFailed, $data);

                } catch (QueryException $ex) {

                    $data = [];
                    $data['item'] = $item;
                    $data['error']['code'] = $ex->getCode();
                    $data['error']['message'] = $ex->getPrevious()->getMessage();

                    array_push($itemsFailed, $data);
                }

            }
        }

        $data = [
            'saved' => $itemsUpdated,
            'invalid' => $itemsInvalid,
            'failed' => $itemsFailed,
        ];

        $message = "";

        if (count($itemsUpdated) == 0) {
            $message = "No item has been updated";
        } else if (count($itemsUpdated) > 0 && (count($itemsInvalid) > 0 || count($itemsFailed) > 0)) {
            $message = "Some items have not been updated";
        } else if (count($itemsUpdated) > 0 && (count($itemsInvalid) == 0 || count($itemsFailed) == 0)) {
            $message = "All items updated";
        }

        return $this->generalService->processResponse($message, $data);

    }

    public function delete($args)
    {


        $entity = $this->generalService->capitalizeEntity($args['entity']);
        $items = $args['items'];

        $itemsDeleted = [];
        $itemsInvalid = [];
        $itemsFailed = [];

        $model = $this->modelService->getModel($entity);

        if (!$model) {
            return $this->generalService->processResponse("The entity does not exist");
        }

        $fields = $this->modelService->getFields($entity);

        $fieldsToRemove = [
            'created_at',
            'updated_at'
        ];

        $this->generalService->removeItemsFromArray($fields, $fieldsToRemove);

        foreach ($items as $item) {

            $validate = Validator::make($item, $model->validations['update']);

            // Items that failed validation
            if ($validate->fails()) {

                $data = [];
                $data['item'] = $item;
                $data['errors'] = $validate->errors();

                array_push($itemsInvalid, $data);

            } else {

                try {

                    $itemToDelete = $model::find($item['id']);
                    $itemToDelete->delete();

                    array_push($itemsSaved, $item);

                } catch (ErrorException $ex) {

                    $data = [];
                    $data['item'] = $item;
                    $data['error']['code'] = $ex->getCode();
                    $data['error']['message'] = $ex->getMessage();

                    array_push($itemsFailed, $data);

                } catch (QueryException $ex) {

                    $data = [];
                    $data['item'] = $item;
                    $data['error']['code'] = $ex->getCode();
                    $data['error']['message'] = $ex->getPrevious()->getMessage();

                    array_push($itemsFailed, $data);
                }

            }
        }

        $data = [
            'saved' => $itemsDeleted,
            'invalid' => $itemsInvalid,
            'failed' => $itemsFailed,
        ];

        $message = "";

        if (count($itemsDeleted) == 0) {
            $message = "No item has been deleted";
        } else if (count($itemsDeleted) > 0 && (count($itemsInvalid) > 0 || count($itemsFailed) > 0)) {
            $message = "Some items have not been deleted";
        } else if (count($itemsDeleted) > 0 && (count($itemsInvalid) == 0 || count($itemsFailed) == 0)) {
            $message = "All items deleted";
        }

        return $this->generalService->processResponse($message, $data);

    }


}
