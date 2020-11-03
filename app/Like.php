<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //テーブル名
    protected $table = 'likes';

    //userとのリレーション
    public function user()
    {
        return $this->belongTo('App\User');
    }

    //outfitとのリレーション
    public function outfit()
    {
        return $this->belongTo('App\Outfit');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','outfit_id'
    ];

    protected $guarded = [
        'id'
    ];
}
