<?php

namespace App\Http\Traits;

use App\Http\Resources\CategoryResource;

trait ApiResponseTrait
{
    public function customeRespone($data, $message, $status) {
        $array = [
            'data'=>$data,
            'message'=>$message
        ];

        return response()->json($array, $status);
    }
}
