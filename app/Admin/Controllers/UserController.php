<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(trans('admin.index'))
            ->description(trans('admin.description'))
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header(trans('admin.detail'))
            ->description(trans('admin.description'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.edit'))
            ->description(trans('admin.description'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(trans('admin.create'))
            ->description(trans('admin.description'))
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->id('ID');
        $grid->column('avatar', '头像')->image('',45,45);
        $grid->name('用户名');
        $grid->email('邮箱');
        $states = [
            'on'  => ['value' => 0, 'text' => '正常', 'color' => 'success'],
            'off' => ['value' => 1, 'text' => '禁用', 'color' => 'danger'],
        ];
        $grid->email_verified_at('邮箱状态')->display(function($email_verified_at, $column){
            if($email_verified_at){
                return "已验证";
            }else{
                return "未验证";
            }
        });
        $grid->column('status','用户状态')->switch($states);
        $grid->column('created_at','创建时间')->date('Y-m-d H:i:s')->sortable();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->column('avatar', '头像')->image('',45,45);
        $show->name('用户名');
        $show->email('邮箱');
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->text('name', '用户名');
        $form->text('email', '邮箱');
        $form->file('avatar', '头像');
        $form->radio('status', '用户状态')->options(['0' => '正常', '1'=> '禁用'])->default('0');
        $form->datetime('email_verified_at', '邮箱状态');
        return $form;
    }
}
