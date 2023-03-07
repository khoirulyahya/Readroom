<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    public function books() //karena tipenya hasMany (jamak) maka nama method pakai's'
    {
        return $this->hasMany('App\Models\Book','author_id');
    }
}
