<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
   protected $table = 'musics';
   /**
    * 只显示发布的音乐
    */
   public function scopeReleased($query)
   {
      return $query->where('released', '=', 1);
   }

   protected function getCoverAttribute($value)
   {
      return "https://tiangong2.wepromo.cn/" . $value;
   }

   protected function getSourceAttribute($value)
   {
      return "https://tiangong2.wepromo.cn/" . $value;
   }

   /**
    * 获取音乐的图片信息
    */
   public function image()
   {
      return $this->belongsTo('\App\Models\Image');
   }

   public function user()
   {
      return $this->belongsTo('\App\Models\User');
   }
}
