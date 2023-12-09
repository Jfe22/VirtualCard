<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    //

    public function index() {
      return TransactionResource::collection(Transaction::all());
    }

    public function show(Transaction $transaction) {
      return new TransactionResource($transaction);
    }

    public function store(Request $request) {
      $transaction = Transaction::create($request->all()); //request->validaded 
      return new TransactionResource($transaction);
    }

    public function update(Request $request, Transaction $transaction) {
      $transaction->update($request->all());
      return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction) {
      $transaction->delete();
      return response()->json();
    }

}
