<?php

namespace Hosein\Galleries;

use Illuminate\Database\Eloquent\Model;

class CategoryGallery extends Model
{
    protected $fillable=[
        'id','name','picture','type'
    ];
}
