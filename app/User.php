<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    // リレーション
    public function clothes(){
        return $this->hasMany('App\Cloth');
    }
    public function profiles(){
        return $this->hasOne('App\Profile');
    }
    public function outfits(){
        return $this->hasMany('App\Outfit');
    }
    public function likes(){
        return $this->hasMany('App\Like');
    }
    public function followers(){
        return $this->belongsToMany('App\User','follows','followee_id','follower_id')->withTimestamps();
    }
    public function followings(){
        return $this->belongsToMany('App\User','follows','follower_id','followee_id')->withTimestamps();
    }

    //メゾッド
    public function isFollowedBy(?User $user):bool
    {
        return $user
            ? (bool)$this->followers->where('id',$user->id)->count() 
            :false;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}

