<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property\PropertyType;

class GetTypes extends Controller
{
    /**
     * Get the property types
     *
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request)
    {
        return $this->response->array(PropertyType::all()->toArray());
    }
}