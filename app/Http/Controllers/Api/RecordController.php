<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Record;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Api\RecordResource;
use App\Http\Requests\Api\Record\RecordStoreRequest;

class RecordController extends Controller
{
    public function index()
    {
        try {

            $record = Record::with('comments')->get(['id','type','description']);

            if (count($record) <= 0) {

                return $this->errorResponse(null, self::BAD_REQUEST);
            }

            return RecordResource::collection($record);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function store(RecordStoreRequest $request)
    {
        try {

            $record = new Record;
            $record->type        = $request->type;
            $record->description = $request->description;
            $record->save();

            return $this->successResponse(null, self::OK);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {

            $record = Record::select('id','type','description')->with('comments')->where('id',$id)->first();

            if (empty($record)) {

                return $this->errorResponse(null, self::BAD_REQUEST);
            }

            return new RecordResource($record);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
