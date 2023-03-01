<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['name','email', 'password'];

    public function products()
    {
        return $this->hasMany(Product::class, 'added_by');
    }
}
