<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cluster;
use App\Models\Transaction;
use Illuminate\Http\Response;

class ClusterRemove extends Controller
{
    /**
     * Remove cluster
     *
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $uid = $request->user()->id;

        $cluster = Cluster::where('user_id', $uid)->findOrFail($request->cluster_id);

        $cluster->delete();

        return null;
    }
}