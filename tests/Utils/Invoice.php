<?php

namespace Tests\Utils;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'description',
        'subtotal',
        'total',
    ];
}
