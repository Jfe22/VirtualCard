<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Vcard extends Model
{
  use HasFactory;


  public function transactions()
  {
      return $this->hasMany(Transaction::class, 'vcard');
  }

  public function categories()
  {
    return $this->hasMany(Category::class, 'category_id');
  }

}