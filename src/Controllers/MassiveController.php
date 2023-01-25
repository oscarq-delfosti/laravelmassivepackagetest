<?php

namespace Oscar\Massive\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HasResponse;

use Oscar\Massive\Services\MassiveService;

use Oscar\Massive\Requests\Massive\{CreateRequest, UpdateRequest, DeleteRequest};

class MassiveController extends Controller
{

    use HasResponse;

    private $massiveService;

    public function __construct()
    {
        $this->massiveService = new MassiveService();
    }

    public function create(CreateRequest $request)
    {

        $body = $request->all();

        $response = $this->massiveService->create($body);

        return $this->successResponse($response);

    }

    public function update(UpdateRequest $request)
    {

        $body = $request->all();

        $response = $this->massiveService->update($body);

        return $this->successResponse($response);
    }

    public function delete(DeleteRequest $request)
    {

        $body = $request->all();

        $response = $this->massiveService->delete($body);

        return $this->successResponse($response);
    }
}
