<?php

namespace App\Model;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'user_id';

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeInYearsAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->dob)->diff(Carbon::now())->y;
    }
}
