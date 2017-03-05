<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'user_id';

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
