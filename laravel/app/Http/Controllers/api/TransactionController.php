<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Models\Vcard;
use App\Http\Resources\TransactionResource;

use App\Http\Requests\StoreUpdateTransactionRequest;

class TransactionController extends Controller
{
    //

    public function index() {
      return TransactionResource::collection(Transaction::all());
    }

    public function show(Transaction $transaction) {
      return new TransactionResource($transaction);
    }

    public function store(StoreUpdateTransactionRequest $request) {
      $transaction = Transaction::create($request->validated());
      return new TransactionResource($transaction);
    }

    public function update(StoreUpdateTransactionRequest $request, Transaction $transaction) {
      $transaction->update($request->validated());
      return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction) {
      $transaction->delete();
      return response()->json();
    }

    public function getTransactionsOfVcard(Vcard $vcard) {
      return TransactionResource::collection($vcard->transactions);
    }

}
