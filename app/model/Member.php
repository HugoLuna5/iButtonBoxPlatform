<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    //

    public function user(){
        return $this->hasOne('App\User', 'id','id_user');
    }

}
