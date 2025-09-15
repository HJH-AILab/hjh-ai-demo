<?php

namespace App\Admin\Controllers;

use App\Models\Video;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class VideoController extends Controller
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
        $grid = new Grid(new Video);

        $grid->id('ID');
        $grid->column('thumb640')->image('https://tiangong2.wepromo.cn/', 150, 150);
        $grid->desc('图片说明');
        $grid->keywords('标签');
        $states = [
            'on'  => ['value' => 1, 'text' => '发布', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '撤回', 'color' => 'danger'],
        ];
        $grid->column('released', '发布状态')->switch($states);
        // $grid->lens('镜头');
        // $grid->size('大小');
        // $grid->resolution('分辨率');
        // $grid->aspect_ratio('宽高比');
        // $grid->colour('颜色');
        // $grid->user_id('user_id');
        // $grid->thumb('thumb');
        $grid->created_at('创建时间')->sortable();
        $grid->views('views')->sortable();
        // $grid->updated_at(trans('admin.updated_at'));

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
        $show = new Show(Video::findOrFail($id));

        $show->id('ID');
        $show->lens('lens');
        $show->size('size');
        $show->resolution('resolution');
        $show->aspect_ratio('aspect_ratio');
        $show->colour('colour');
        $show->user_id('user_id');
        $show->thumb('thumb');
        $show->url('url');
        $show->created_at(trans('admin.created_at'));
        $show->updated_at(trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Video);

        //$form->display('ID');
        $form->text('desc', '图片说明');
        $form->text('lens', '镜头');
        $form->text('size', '大小');
        $form->text('resolution', '分辨率');
        $form->text('aspect_ratio', '宽高比');
        $form->text('colour', '颜色');
        //$form->text('user_id', '创建者');
        $form->image('thumb', '缩略图')->removable();
        $form->tags('keywords', '标签');
        $form->radio('released', '发布状态')->options(['0' => '暂停发布', '1' => '发布'])->default('0');
        // $form->display(trans('admin.created_at'));
        // $form->display(trans('admin.updated_at'));

        return $form;
    }
}
