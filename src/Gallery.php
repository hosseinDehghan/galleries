<?php

namespace Hosein\Galleries;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable=[
        'id','title','picture','paths','type',
        'details','like','dislike','visited','category_id'
    ];
}
