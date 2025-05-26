<?php

namespace App\Admin\Controllers;

use App\Models\Copy;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CopyController extends Controller
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
        $grid = new Grid(new Copy);

        $grid->id('ID');
        $grid->lens('lens');
        $grid->size('size');
        $grid->resolution('resolution');
        $grid->aspect_ratio('aspect_ratio');
        $grid->colour('colour');
        $grid->desc('desc');
        $grid->released('released');
        $grid->url('url');
        $grid->created_at(trans('admin.created_at'));
        $grid->updated_at(trans('admin.updated_at'));

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
        $show = new Show(Copy::findOrFail($id));

        $show->id('ID');
        $show->lens('lens');
        $show->size('size');
        $show->resolution('resolution');
        $show->aspect_ratio('aspect_ratio');
        $show->colour('colour');
        $show->desc('desc');
        $show->released('released');
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
        $form = new Form(new Copy);

        $form->display('ID');
        $form->text('lens', 'lens');
        $form->text('size', 'size');
        $form->text('resolution', 'resolution');
        $form->text('aspect_ratio', 'aspect_ratio');
        $form->text('colour', 'colour');
        $form->text('desc', 'desc');
        $form->text('released', 'released');
        $form->text('url', 'url');
        $form->display(trans('admin.created_at'));
        $form->display(trans('admin.updated_at'));

        return $form;
    }
}
