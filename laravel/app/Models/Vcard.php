<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Vcard extends Model
{
  use HasFactory;

  protected $fillable = [
    'phone_number',
    'name',
    'email',
    'password',
    'confirmation_code',
    'blocked',
    'balance',
    'max_debit',
  ];

  protected $hidden = [
    'password',
    'confirmation_code',
  ];

  protected $casts = [
    'password' => 'hashed',
    'confirmation_code' => 'hashed',
  ];

  


  public function transactions()
  {
      return $this->hasMany(Transaction::class, 'vcard');
  }

  public function categories()
  {
    return $this->hasMany(Category::class, 'category_id');
  }

  public function getRouteKeyName()
  {
    return 'phone_number';
  }

}