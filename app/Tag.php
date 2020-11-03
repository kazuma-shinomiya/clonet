<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Outfitとのリレーション
     */
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
        'name'
    ];

    protected $guarded = [
        'id'
    ];
}
