<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $table = 't_image';

    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function jobnumber() {
        return $this->hasOne(JobNumber::class, 'id', 'jobnumber_id');
    }
}
