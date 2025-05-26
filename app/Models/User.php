<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Jobs\SendResetPasswordEmail;
use Overtrue\LaravelFavorite\Traits\Favoriter;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Favoriter;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 获取用户图片
     */
    public function images(){
        return $this->hasMany('\App\Models\Image');
    }

    public function musics(){
        return $this->hasMany('\App\Models\Music');
    }

    /**
    * Send the password reset notification.
    *
    * @param  string $token
    *
    * @return void
    */
    public function sendPasswordResetNotification($token)
    {
        dispatch(new SendResetPasswordEmail($this, $token));
    }

    /**
     * 设置头像地址
     */ 
    protected function getAvatarAttribute($value){
        if($value==null){
            return "https://tiangong2.wepromo.cn/avatars/default.jpg";
        }else{
            return "https://tiangong2.wepromo.cn/".$value;
        }
    }

}
