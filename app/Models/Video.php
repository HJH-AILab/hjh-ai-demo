<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Overtrue\LaravelFavorite\Traits\Favoriteable;
use Laravel\Scout\Searchable;

class Video extends Model
{
    use Searchable, Favoriteable;

    /**
     * 获取模型的索引名称.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'videos_index';
    }

    public function shouldBeSearchable()
    {
        if ($this->released) return true;
        return false;
    }

    /**
     * Thumb字段访问器,防止后台修改原字段后台无法删除oss图片问题
     */

    protected function getNewThumbAttribute()
    {
        return "https://tiangong2.wepromo.cn/" . $this->thumb;
    }

    protected function getNewUrl()
    {
        return "https://tiangong2.wepromo.cn/" . $this->url;
    }


    /**
     * 获取图片用户信息
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }

    /**
     * 只显示发布的图片
     */
    public function scopeReleased($query)
    {
        return $query->where('released', '=', 1);
    }

    /**
     * keywords访问器
     */
    public function getKeywordsAttribute($value)
    {
        return explode(',', $value);
    }
}
