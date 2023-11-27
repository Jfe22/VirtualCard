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
}
