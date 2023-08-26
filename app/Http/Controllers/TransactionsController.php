<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionsResource;
use App\Models\Patient;
use App\Models\Transaction;
use App\Notifications\NewBillNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionsController extends Controller
{

    public function all(Request $request)
    {
        return TransactionsResource::collection(
            Transaction::with(['patient'])->paginate(20)
        );
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $transaction = new Transaction();
            $transaction->transaction_code = "TR".Str::upper(Str::random(8));
            $transaction->patient_code = $request->patient_code;
            $transaction->amount = $request->amount;
            $transaction->service = $request->service;
            $transaction->status = $request->status;
            $transaction->save();

            if($transaction)
            {
                DB::commit();

                return response()->json([
                    'api_code' => 'SUCCESS',
                    'api_msg' => 'Transaction Record created successfully.',
                    'api_status' => true,
                    'data' => [
                        'transaction' => new TransactionsResource($transaction)
                    ]
                ], 200);
            }

            throw new \Exception('Transaction Record not created');
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'api_code' => 'FAILED',
                'api_msg' => 'Transaction Record creation failed.',
                'api_status' => false,
                'data' => [
                    'error' => $th->getMessage()
                ]
            ], 500);
        }
    }

    public function paid(Request $request)
    {
        # code...
    }
}
