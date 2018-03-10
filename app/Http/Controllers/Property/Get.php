<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property\Property;

class Get extends Controller
{
    /**
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request)
    {
        return $this->response->array(Property::all()->toArray());
    }
}