<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\TransactionCreateRequest;
use App\Models\Announcement;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     tags={"Transactions"},
     *     summary="Get all transactions of the authenticated user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of transactions",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="transactions",
     *                 type="array",
     *                 @OA\Items(type="object", example={"id": 1, "buyer_id": 42, "seller_id": 15, "final_price": 100, "created_at": "2025-01-01T12:00:00Z"})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     )
     * )
     */
    public function getAllTransactions(Request $request)
    {
        $user = $this->authenticateUser($request);
        $transactions = $user->transactions;

        return response()->json([
            'transactions' => $transactions,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/transactions/{id}/create",
     *     tags={"Transactions"},
     *     summary="Create a transaction for an announcement",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the announcement",
     *         @OA\Schema(type="integer", example=42)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="full_name", type="string", example="John Doe"),
     *             @OA\Property(property="card_expiration", type="string", example="12/25"),
     *             @OA\Property(property="card_number", type="string", example="1234567812345678"),
     *             @OA\Property(property="card_cvv", type="string", example="123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction successfully created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Transaction successfully created"),
     *             @OA\Property(property="transaction", type="object", example={
     *                 "id": 1,
     *                 "buyer_id": 42,
     *                 "seller_id": 15,
     *                 "final_price": 100,
     *                 "created_at": "2025-01-01T12:00:00Z"
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Cannot create transaction because the ad is yours"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Announcement not found"
     *     )
     * )
     */
    public function createTransaction(TransactionCreateRequest $request, $id)
    {
        $user = $this->authenticateUser($request);
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

        $announcement->state = 'purchased';
        $announcement->save();

        return response()->json([
            'transaction' => $transaction,
            'message' => 'Transaction successfully created'
        ], 201);
    }


    /**
     * @OA\Get(
     *     path="/api/transactions/{id}",
     *     tags={"Transactions"},
     *     summary="Get a specific transaction by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the transaction",
     *         @OA\Schema(type="integer", example=42)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="transaction", type="object", example={"id": 1, "buyer_id": 42, "seller_id": 15, "final_price": 100, "created_at": "2025-01-01T12:00:00Z"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You are not authorized to view this transaction"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     )
     * )
     */
    public function getTransaction(Request $request, $id)
    {
        $user = $this->authenticateUser($request);
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
