<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\VcardResource;
use App\Models\Vcard;


class VcardController extends Controller
{
    //
    public function index() {
      return VcardResource::collection(Vcard::all());
    }

    public function show(Vcard $vcard) {
      return new VcardResource($vcard);
    }

    //redo with specific request type
    public function store(Request $request) {
      $vcard = Vcard::create($request->all()); //request->validaded 
      return new VcardResource($vcard);
    }

    //also needs specific request type
    public function update(Request $request, Vcard $vcard) {
      $vcard->update($request->all());
      return new VcardResource($vcard);
    }

    public function destroy(Vcard $vcard) {
      //delete also database relations?? like associated transactions???
      $vcard->delete();
      return response()->json();
    }
}
