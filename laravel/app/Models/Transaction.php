<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


    public function vcard()
    {
        return $this->belongsTo(Vcard::class, 'phone_number');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id');
    }

}