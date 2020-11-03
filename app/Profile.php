<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //テーブル名
    protected $table = 'profiles';

    //リレーション
    Public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gender', 'height','comment','user_id','image',
    ];

    protected $guarded = [
        'id'
    ];
}
