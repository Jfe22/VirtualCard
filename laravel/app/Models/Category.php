<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use HasFactory, SoftDeletes;


    public function vcard()
    {
        return $this->belongsTo(Vcard::class, 'phone_number');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }

}
