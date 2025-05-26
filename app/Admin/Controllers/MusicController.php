<?php

namespace App\Admin\Controllers;

use App\Models\Music;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class MusicController extends Controller
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
        $grid = new Grid(new Music);

        $grid->id('ID');
        $grid->name('歌曲名称');
        $grid->artist('歌曲作者');
        $grid->cover('歌曲封面')->image('',100,100);
        $grid->desc('图片说明');
        $states = [
            'on'  => ['value' => 1, 'text' => '发布', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '撤回', 'color' => 'danger'],
        ];
        $grid->column('released','发布状态')->switch($states);
        $grid->created_at('创建时间')->sortable();
        // $grid->updated_at(trans('admin.updated_at'));
        $grid->actions(function ($actions) {
            $actions->disableView();
        });
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
        $show = new Show(Music::findOrFail($id));

        $show->id('ID');
        $show->lens('lens');
        $show->size('size');
        $show->resolution('resolution');
        $show->aspect_ratio('aspect_ratio');
        $show->colour('colour');
        $show->user_id('user_id');
        $show->thumb('thumb');
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
        $form = new Form(new Music);

        //$form->display('ID');
        $form->text('name', '歌曲名称');
        $form->text('artist', '歌曲作者');
        $form->editormd('desc');
        $form->image('cover', '歌曲封面')->removable();
        $form->file('source', '歌曲文件')->removable();
        $form->radio('released', '发布状态')->options(['0' => '暂停发布', '1'=> '发布'])->default('0');
        // $form->display(trans('admin.created_at'));
        // $form->display(trans('admin.updated_at'));

        return $form;
    }
}
