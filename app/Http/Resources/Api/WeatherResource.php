<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Api\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'id' => $this->id,
            'city' => $this->city,
            'temperature' => $this->temperature,
            'temperature_min' => $this->temperature_min,
            'temperature_max' => $this->temperature_max,
            'humidity' => $this->humidity,
            'temperature_fahrenheit' => $this->fahrenheit,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
