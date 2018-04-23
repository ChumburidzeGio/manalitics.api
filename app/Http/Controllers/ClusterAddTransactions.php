<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cluster;
use App\Models\Transaction;
use Illuminate\Http\Response;

class ClusterAddTransactions extends Controller
{
    /**
     * Add transactions to existing cluster or to the new one
     *
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $uid = $request->user()->id;

        if($request->has('cluster_id')) {
            $cluster = Cluster::where('user_id', $uid)->findOrFail($request->cluster_id);
        }
        elseif ($request->has('name')) {
            $cluster = Cluster::create([
                'user_id' => $uid,
                'name' => $request->name
            ]);
        }
        else {
            throw new \Exception('There should be or cluster id or at last name of new cluster');
        }

        if(!$request->has('transaction_ids')) {
            return $cluster;
        }

        $transaction_ids = Transaction::whereIn('id', $request->transaction_ids)->where('user_id', $uid)->pluck('id');

        $cluster->transactions()->syncWithoutDetaching($transaction_ids);

        return $cluster;
    }
}