<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreCheckin extends Model
{
    public $timestamps = false;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_checkin';

    /**
     * The aditional attribute: getNameAttribute()
     *
     * @var arrray
     */
    protected $appends = array();

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'idStore', 'idAccount', 'date', 'message', 'users'];
}
