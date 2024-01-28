<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Record;
use App\Models\Api\Comment;
use App\Models\Api\Weather;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Comment\CommentRecordRequest;
use App\Http\Requests\Api\Comment\CommentWeatherRequest;

class CommentController extends Controller
{
    public function storeWeather(CommentWeatherRequest $request)
    {
        try {

            $weather = Weather::find($request->city_id);

            $comment = new Comment;
            $comment->comment = $request->comment;

            $weather->comments()->save($comment);

            return $this->successResponse(null, self::OK);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function storeRecord(CommentRecordRequest $request)
    {
        try {

            $record = Record::find($request->record_id);

            $comment = new Comment;
            $comment->comment = $request->comment;

            $record->comments()->save($comment);

            return $this->successResponse(null, self::OK);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
