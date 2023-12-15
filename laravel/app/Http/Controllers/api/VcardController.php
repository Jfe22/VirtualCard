<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\api\TransactionController;
use Illuminate\Http\Request;

use App\Http\Resources\VcardResource;
use App\Models\Vcard;

use App\Http\Requests\StoreUpdateVcardRequest;
use App\Http\Requests\UpdateBalanceVcardRequest;


class VcardController extends Controller
{
    //
    public function index() {
      return VcardResource::collection(Vcard::all());
    }

    public function show(Vcard $vcard) {
      return new VcardResource($vcard);
    }

    public function store(StoreUpdateVcardRequest $request) {
      $vcard = Vcard::create($request->validated()); 
      return new VcardResource($vcard);
    }

    public function update(StoreUpdateVcardRequest $request, Vcard $vcard) {
      $vcard->update($request->validated());
      return new VcardResource($vcard);
    }

    public function destroy(Vcard $vcard) {
      if ($vcard->balance != 0)
        return response()->json(['error' => 'Não é possível apagar um cartão com saldo diferente de zero.'], 403);

      if ($vcard->transactions()->count() > 0) {
        foreach ($vcard->transactions as $transaction) {
          TransactionController::destroy($transaction);
        }
        $vcard->delete();

      } else
        $vcard->forceDelete();

      return response()->json();
    }

    public function update_balance(UpdateBalanceVcardRequest $request, Vcard $vcard) {
      $vcard->balance = $request->validated()['balance'];
      $vcard->save();
      return new VcardResource($vcard);
    }
}
