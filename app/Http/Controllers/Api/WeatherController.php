<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Weather;
use App\Services\OpenWeatherMapService;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Api\WeatherResource;
use App\Http\Requests\Api\Weather\WeatherStoreRequest;

class WeatherController extends Controller
{
    protected $openWeatherMapService;

    public function __construct(OpenWeatherMapService $openWeatherMapService)
    {
        $this->openWeatherMapService = $openWeatherMapService;
    }

    public function index()
    {
        try {

            $weather = Weather::with('comments')->get(['id','city','temperature','temperature_min','temperature_max','humidity']);

            if (count($weather) <= 0) {

                return $this->errorResponse(null, self::BAD_REQUEST);
            }

            return WeatherResource::collection($weather);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function store(WeatherStoreRequest $request)
    {
        try {

            $data = $this->openWeatherMapService->getWeatherByCity($request->city);

            if (isset($data->original)) {

                $response = $data->original;

                $status = $response['status'];

                return $this->errorResponse(null, $status);
            }

            $validateWeather = Weather::where('city',$data->name)->first();

            if (empty($validateWeather)) {

                $weather = new Weather;
                $weather->city              = $data->name;
                $weather->temperature       = $data->main->temp;
                $weather->temperature_min   = $data->main->temp_min;
                $weather->temperature_max   = $data->main->temp_max;
                $weather->humidity          = $data->main->humidity;
                $weather->save();

            } else {

                $weather = Weather::where('city',$data->name)->first();
                $weather->city              = $data->name;
                $weather->temperature       = $data->main->temp;
                $weather->temperature_min   = $data->main->temp_min;
                $weather->temperature_max   = $data->main->temp_max;
                $weather->humidity          = $data->main->humidity;
                $weather->save();
            }

            return $this->successResponse(null, self::OK);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {

            $weather = Weather::select('id','city','temperature','temperature_min','temperature_max','humidity')->with('comments')->where('id',$id)->first();

            if (empty($weather)) {

                return $this->errorResponse(null, self::BAD_REQUEST);
            }

            return new WeatherResource($weather);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
