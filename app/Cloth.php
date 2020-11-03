<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Cloth extends Model
{
    //テーブル名
    protected $table = 'clothes';

    //userとのリレーション
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    //outfitとのリレーション
    public function outfits()
    {
        return $this->belongsToMany('App\Outfit');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'category','size','brand','buy_date','price','image','user_id',
    ];

    protected $guarded = [
        'id'
    ];
}
