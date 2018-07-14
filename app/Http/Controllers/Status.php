<?php

namespace App\Http\Controllers;

class Status extends Controller
{
    /**
     * Get application status
     * @return array
     */
    public function __invoke()
    {
        return [
            'success' => true
        ];
    }

}