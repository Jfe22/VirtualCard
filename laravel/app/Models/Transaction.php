<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'vcard',
      'date',
      'datetime',
      'type',
      'value',
      'old_balance',
      'new_balance',
      'payment_type',
      'payment_reference',
      'pair_transaction',
      'pair_vcard',
      'category_id',
      'description',
    ];

    public function vcard()
    {
      return $this->belongsTo(Vcard::class, 'phone_number');
    }

    public function category()
    {
      return $this->belongsTo(Category::class, 'id');
    }

}
