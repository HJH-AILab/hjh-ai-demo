<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\User;
use App\Models\Music;
use App\Models\Video;
use Illuminate\Support\Facades\Redis;
use Elasticsearch\Client;

class PagesController extends Controller
{

    /**
     * 首页展示,音图话
     */
    public function index()
    {
        $m = Music::Released()->orderBy('id', 'desc')->first();
        if ($m === null) {
            $id = 1;
        } else {
            $id = $m->image_id;
        }
        $image = Image::findOrFail($id);
        $music = $image->musics()->Released()->get();
        $list = [];
        foreach ($music as $key => $row) {
            $list[$key]['name'] = $row->name;
            $list[$key]['artist'] = $row->artist;
            $list[$key]['cover'] = $row->cover;
            $list[$key]['source'] = $row->source;
            $list[$key]['desc'] = $row->desc;
            $list[$key]['auth'] = $row->user->name;
        }
        return view(
            'pages.index',
            [
                'image' => $image,
                'list' => json_encode($list),
                'title' => '首页'
            ]
        );
    }


    /**
     * 首页展示,音图话
     */
    public function music($id)
    {
        $image = Image::findOrFail($id);
        $music = $image->musics()->Released()->get();
        $list = [];
        foreach ($music as $key => $row) {
            $list[$key]['name'] = $row->name;
            $list[$key]['artist'] = $row->artist;
            $list[$key]['cover'] = $row->cover;
            $list[$key]['source'] = $row->source;
            $list[$key]['desc'] = $row->desc;
            $list[$key]['auth'] = $row->user->name;
        }

        return view(
            'pages.index',
            [
                'image' => $image,
                'list' => json_encode($list),
                'title' => '首页'
            ]
        );
    }

    /**
     * 
     * 
     */
    public function list()
    {

        $res = Image::has('musics')->orderBy('created_at', 'desc')->Released()->simplePaginate(24);
        return view(
            'pages.list',
            [
                'images' => $res,
                'title' => '音图话'
            ]
        );
    }

    /**
     * 图片列表页展示
     * 
     */
    public function root()
    {
        $page = request('page');
        if (!$page) {
            $page = 1;
        }
        $cacheKey = "images:list:" . $page;
        if (Redis::exists($cacheKey)) {
            $res = Redis::get($cacheKey);
            $res = unserialize($res);
        } else {
            $res = Image::orderBy('created_at', 'desc')->Released()->simplePaginate(24, ['*'], 'page', $page);
            Redis::setex($cacheKey, 3600 * mt_rand(1, 24), serialize($res));
        }
        return view(
            'pages.root',
            [
                'images' => $res,
                'title' => '图片'
            ]
        );
    }

    /**
     * 详细图片页面展示
     * 
     */
    public function show($id)
    {
        Image::findOrFail($id)->increment('views');
        $cacheKey = "images:show:" . $id;
        if (Redis::exists($cacheKey)) {
            $res = Redis::get($cacheKey);
            $res = unserialize($res);
        } else {
            $res = Image::findOrFail($id);
            Redis::setex($cacheKey, 3600 * mt_rand(1, 24), serialize($res));
        }

        $cacheUserKey = "images:morelist:" . $id;
        if (Redis::exists($cacheUserKey)) {
            $more = Redis::get($cacheUserKey);
            $more = unserialize($more);
        } else {
            $more = Image::inRandomOrder()->Released()->limit(12)->get();
            Redis::setex($cacheUserKey, 3600 * mt_rand(1, 24), serialize($more));
        }

        return view(
            'pages.show',
            [
                'image' => $res,
                'more' => $more
            ]
        );
    }

    /**
     * 详细图片页面展示
     * 
     */
    public function show1($id)
    {
        Video::findOrFail($id)->increment('views');
        $cacheKey = "videos:show:" . $id;
        if (Redis::exists($cacheKey)) {
            $res = Redis::get($cacheKey);
            $res = unserialize($res);
        } else {
            $res = Video::findOrFail($id);
            Redis::setex($cacheKey, 3600 * mt_rand(1, 24), serialize($res));
        }

        $cacheUserKey = "videos:morelist:" . $id;
        if (Redis::exists($cacheUserKey)) {
            $more = Redis::get($cacheUserKey);
            $more = unserialize($more);
        } else {
            $more = Video::inRandomOrder()->Released()->limit(12)->get();
            Redis::setex($cacheUserKey, 3600 * mt_rand(1, 24), serialize($more));
        }

        return view(
            'pages.show1',
            [
                'video' => $res,
                'more' => $more
            ]
        );
    }

    /**
     * 网站版本权限说明
     */
    public function license()
    {
        return view(
            'pages.license',
            [
                'title' => '版本说明'
            ]
        );
    }

    /**
     * 我为图片配音乐说明
     */
    public function play()
    {
        return view(
            'pages.play',
            [
                'title' => '玩法说明'
            ]
        );
    }

    /**
     * 展示用户上传的图片
     */
    public function user($id)
    {
        $cacheKey = "images:user:" . $id;
        if (Redis::exists($cacheKey)) {
            $user = Redis::get($cacheKey);
            $user = unserialize($user);
        } else {
            $user = User::findOrFail($id);
            Redis::setex($cacheKey, 3600 * mt_rand(1, 24), serialize($user));
        }

        $page = request('page');
        if (!$page) {
            $page = 1;
        }
        $cacheUserKey = "images:userlist:" . $id . ":" . $page;
        if (Redis::exists($cacheUserKey)) {
            $res = Redis::get($cacheUserKey);
            $res = unserialize($res);
        } else {
            $res = $user->images()->orderBy('created_at', 'desc')->Released()->simplePaginate(24, ['*'], 'page', $page);
            Redis::setex($cacheUserKey, 3600 * mt_rand(1, 24), serialize($res));
        }

        return view(
            'pages.user',
            [
                'images' => $res,
                'user' => $user,
                'title' => $user->name
            ]
        );
    }

    /**
     * 展示keywords的图片
     */
    public function tag($name)
    {
        $res = Image::where('keywords', 'like', "%$name%")->orderBy('created_at', 'desc')->Released()->simplePaginate(24);
        return view(
            'pages.tag',
            [
                'images' => $res,
                'title' => $name,
                'tag' => $name
            ]
        );
    }

    /**
     * 关键词搜索
     */
    public function find(Request $request)
    {
        $query = $request->input('query');
        $res = Image::search($query)->simplePaginate(24);

        return view(
            'pages.search',
            [
                'images' => $res,
                'title' => $query,
                'query' => $query
            ]
        );
    }
}
