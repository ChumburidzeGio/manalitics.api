<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ExportToFileParams extends Controller
{
    /**
     * Export transactions into CSV params
     *
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $token = JWTAuth::getToken();

        return [
            'link' => url('api/export.toFile').'?token='.$token
        ];
    }
}