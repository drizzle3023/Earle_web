<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class JobNumber extends Model
{
    //
    protected $table = 't_jobnumber';

    public $timestamps = false;

    public function company() {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
