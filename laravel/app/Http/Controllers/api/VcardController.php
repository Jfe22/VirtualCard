<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\VcardResource;
use App\Models\Vcard;

use App\Http\Requests\StoreUpdateVcardRequest;


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
      //delete also database relations?? like associated transactions???
      $vcard->delete();
      return response()->json();
    }
}
