<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\TransactionCreateRequest;
use App\Models\Announcement;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function getAllTransactions(Request $request)
    {
        $user = $request->user();
        $transactions = $user->transactions;

        return response()->json([
            'transactions' => $transactions,
        ], 200);
    }

    public function createTransaction(TransactionCreateRequest $request, $id)
    {
        $user = $request->user();
        $announcement = Announcement::findOrFail($id);
        if ($announcement->user_id == $user->id) {
            return response()->json([
                'message' => 'You cannot continue with the transaction because the ad is yours.'
            ], 422);
        }

        $validatedData = $request->validated();
        $transaction = new Transaction($validatedData);
        $transaction->buyer_id = $user->id;
        $transaction->seller_id = $announcement->user_id;
        $transaction->final_price = $announcement->price;
        $transaction->announcement_id = $announcement->id;
        $transaction->save();

        return response()->json([
            'transaction' => $transaction,
            'message' => 'Transaction successfully created'
        ], 201);
    }

    public function getTransaction(Request $request, $id) 
    {
        $user = $request->user();
        $transaction = Transaction::findOrFail($id);

        if ($transaction->buyer_id != $user->id && $transaction->seller_id != $user->id) {
            return response()->json([
                'message' => 'You are not authorized to view this transaction'
            ], 403);
        }

        return response()->json([
            'transaction' => $transaction,
        ], 200);
    }
}
