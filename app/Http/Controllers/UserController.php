<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Image;
use App\Models\Music;
use Image as Intervention;
use Storage;

class UserController extends Controller
{

    /**
     * 添加验证中间件
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 显示密码修改框
     *
     * @return void
     */
    public function showRequestForm()
    {
        return view('user.passwords.reset');
    }

    /**
     * 判断密码规则及修改入库
     *
     * @return void
     */
    public function passwordrest(Request $request)
    {

        $request->validate($this->rules(), $this->validationErrorMessages());
        Auth::user()->password = bcrypt($request->get('password'));
        Auth::user()->save();
        return redirect()->back()->withMessage('更新成功');
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'password' => 'required|confirmed|min:8',
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [];
    }

    /**
     * 显示Profile修改框
     *
     * @return void
     */
    public function showProfileForm()
    {
        return view('user.profile');
    }

    /**
     *
     * 更新profile
     * @return void
     */
    public function profile(Request $request)
    {
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $path = $request->file('avatar')->store('avatars');
            Auth::user()->avatar = $path;
        }
        $name = $request->get('name');
        Auth::user()->name = $request->get('name');
        Auth::user()->save();

        return redirect()->back()->withMessage('更新成功');
    }

     /**
     * 显示图片修改框
     * @return void
     */
    public function showImageForm()
    {
        return view('user.image');
    }

    /**
     * 上传图片
     * @return void
     */
    public function image(Request $request)
    {
        $model = new Image();
        if ($request->hasFile('thumb') && $request->file('thumb')->isValid()) {

            $file = $request->file('thumb');
            $model->thumb = $file->store('thumbs');
            $imageName = uniqid(date('YmdHis')).$file->getClientOriginalName();

            //生成1920宽度图片
            $resource1920 = Intervention::make($file)->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream()->detach();
            Storage::disk('local')->put('thumb1920/'.$imageName,$resource1920);
            $model->thumb1920 = 'thumb1920/'.$imageName;

            //生成1280宽度图片
            $resource1280 = Intervention::make($file)->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream()->detach();
            Storage::disk('local')->put('thumb1280/'.$imageName,$resource1280);
            $model->thumb1280 = 'thumb1280/'.$imageName;

            //生成640宽度图片
            $resource640 = Intervention::make($file)->resize(640, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream()->detach();
            Storage::disk('local')->put('thumb640/'.$imageName,$resource640);
            $model->thumb640 = 'thumb640/'.$imageName;

        }
        $model->desc = $request->get('desc');
        $model->lens = $request->get('lens');
        $model->size = $request->get('size');
        $model->resolution = $request->get('resolution');
        $model->aspect_ratio = $request->get('aspect_ratio');
        $model->keywords = $request->get('keywords');
        $model->released = 0;
        $model->user_id = Auth::user()->id;
        $model->save();

        return redirect()->back()->withMessage('图片上传成功,审核通过后展示');
    }

    /**
     * 显示图片配乐表单
     * @return void
     */
    public function showMusicForm(Request $request)
    {
        $image = Image::find($request->id);
        return view('user.music',
            [
                'image' => $image
            ]
        );
    }

    /**
     * 音乐
     * @return void
     */
    public function music(Request $request)
    {
        $model = new Music();
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $file = $request->file('cover');
            $model->cover = $file->store('cover');
        }
        if ($request->hasFile('source') && $request->file('source')->isValid()) {
            $file = $request->file('source');
            $model->source = $file->store('source');
        }
        $model->name = $request->get('name');
        $model->artist = $request->get('artist');
        $model->desc = $request->get('desc');
        $model->image_id = $request->get('image_id');
        $model->released = 0;
        $model->user_id = Auth::user()->id;
        $model->save();

        return redirect()->back()->withMessage('配乐成功,审核通过后展示,您可以继续为该图片或者其它图片配乐');
    }

    /**
     * 用户favorite功能
     */ 

    public function favorite(Request $request)
    {
        $user = Auth::user();
        $post = Image::find($request->id);
        if($user->hasFavorited($post)){
            return json_encode(['msg'=>'hasFavorited']);
        }else{
            $user->favorite($post);
            return json_encode(['msg'=>'success']);
        }
    }


    /**
     * 用户取消favorite功能
     */ 

    public function unfavorite(Request $request)
    {
        $user = Auth::user();
        $post = Image::find($request->id);
        if($user->hasFavorited($post)){
            $user->unfavorite($post);
            return json_encode(['msg'=>'unFavorited']);
        }else{
            return json_encode(['msg'=>'unFavorite']);
        }
    }

    /**
     * 用户收藏的图片列表
     */ 

    public function favorites()
    {
        $user = Auth::user();
        $favortePosts = $user->getFavoriteItems(Image::class)->simplePaginate(24);
        return view('pages.favorite',
            [
                'images' => $favortePosts,
                'title' => $user->name
            ]
        );

    }
    
}
