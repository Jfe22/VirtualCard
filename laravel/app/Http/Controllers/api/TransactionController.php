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
      //$transaction = Transaction::create($request->validated());
      $transaction = new Transaction();
      $transaction->id = Transaction::max('id') + 1;
      $transaction->vcard = $request->validated()['vcard'];
      $transaction->type = $request->validated()['type'];
      $transaction->value = $request->validated()['value'];
      $transaction->payment_type = $request->validated()['payment_type'];
      $transaction->payment_reference = $request->validated()['payment_reference'];
      if($request->filled('category_id'))
        $transaction->category_id = $request->validated()['category_id'];
      if($request->filled('description'))
        $transaction->description = $request->validated()['description'];

      $transaction->date = date('Y-m-d');
      $transaction->datetime = date('Y-m-d H:i:s');

      $vcard = Vcard::where('phone_number', $transaction->vcard)->first();
      $transaction->old_balance = $vcard->balance; 
      if ($transaction->type == 'D')
        $transaction->new_balance = $transaction->old_balance - $transaction->value;
      else
        $transaction->new_balance = $transaction->old_balance + $transaction->value;


      if ($transaction->payment_type == 'VCARD' ) {
        $transaction->pair_vcard = $transaction->payment_reference;
        if ($transaction->is_pair)
          $transaction->pair_transaction = ($transaction->id) - 1 ;
        else
          $transaction->pair_transaction = ($transaction->id) + 1 ;
      }

      $transaction->save();

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
