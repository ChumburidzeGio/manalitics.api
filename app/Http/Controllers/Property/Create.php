<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property\Property;

class Create extends Controller
{
    /**
     * Create a new property.
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $this->authorize('create-properties');

        $this->validate($request, [
            'type_id' => 'required|integer|exists:property_types',
            'name' => 'required|string',
            'rooms' => 'integer|between:1,5',
            'address1' => 'required|string',
            'city' => 'required|string',
            'spoken_languages' => 'required|string',
            'wifi' => 'required|in:0,1,2',
            'parking' => 'required|in:0,1,2',
        ]);

        Property::create(
            $request->all()
        );
    }
}