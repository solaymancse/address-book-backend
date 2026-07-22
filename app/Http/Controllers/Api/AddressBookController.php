<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressBookRequest;
use App\Services\AddressBookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressBookController extends Controller
{

    public function __construct(protected AddressBookService $service) {}

    public function index(Request $request): JsonResponse
    {
        $records = $this->service->listRecords($request->all());
        return successResponse($records, 'Address book records retrieved successfully');
    }

    public function store(AddressBookRequest $request): JsonResponse
    {
        $record = $this->service->createRecord($request->validated(), $request->user()->id);
        return successResponse($record, 'Address book record created successfully', 201);
    }

    public function show(int $id): JsonResponse
    {
        $record = $this->service->getRecord($id);
        if (!$record) {
            return errorResponse('Record not found', 404);
        }
        return successResponse($record, 'Record retrieved successfully');
    }

    public function update(AddressBookRequest $request, int $id): JsonResponse
    {
        $record = $this->service->getRecord($id);
        if (!$record) {
            return errorResponse('Record not found', 404);
        }

        $updated = $this->service->updateRecord($record, $request->validated());
        return successResponse($updated, 'Address book record updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $record = $this->service->getRecord($id);
        if (!$record) {
            return errorResponse('Record not found', 404);
        }

        $this->service->deleteRecord($record);
        return successResponse(null, 'Address book record deleted successfully');
    }
}
