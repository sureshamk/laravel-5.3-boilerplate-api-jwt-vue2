<?php

namespace App\Http\Controllers;

use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    public function validateApiRequest($data, $rules, array $message = [])
    {
        $validator = Validator::make($data, $rules, $message);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
    }
}
