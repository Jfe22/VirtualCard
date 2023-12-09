<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    public function vcard()
    {
        return $this->belongsTo(Vcard::class, 'phone_number');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }

}
