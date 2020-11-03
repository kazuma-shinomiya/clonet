<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outfit extends Model
{
    //テーブル名
    protected $table = 'outfits';

    //userとのリレーション
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    //clothとの多対多リレーション
    public function clothes()
    {
        return $this->belongsToMany('App\Cloth');
    }

    //tagとの多対多リレーション
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    //likeとのリレーション
    public function likes()
    {
        return $this->hasMany('App\Like');
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','user_id','tag_comment'
    ];

    protected $guarded = [
        'id'
    ];
}
