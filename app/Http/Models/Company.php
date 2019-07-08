<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $table = 't_company';

    public $timestamps = false;


    public function jobNumbers()
    {
        return $this->hasMany(JobNumber::class, 'company_id', 'id');
    }
}
