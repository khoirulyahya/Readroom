<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory;

    protected $fillable = ['name']; //Mendaftarkan data yg di insert dengan cara kedua

    public function books() //karena tipenya hasMany (jamak) maka nama method pakai's'
    {
        return $this->hasMany('App\Models\Book','catalog_id');
    }
}
