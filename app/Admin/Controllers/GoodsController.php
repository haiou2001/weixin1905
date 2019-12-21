<?php

namespace App\Admin\Controllers;

use App\Model\GoodsModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GoodsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GoodsModel);

        $grid->column('id', __('商品编号'));
        $grid->column('goods_name', __('商品昵称'));
        $grid->column('img', __('商品图片'))->image();
        $grid->column('price', __('商品价格'));
        $grid->column('desc', __('商品信息'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(GoodsModel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('goods_name', __('Goods name'));
        $show->field('img', __('Img'));
        $show->field('price', __('Price'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GoodsModel);

        $form->text('goods_name', __('商品名称'));
        $form->image('img', __('商品图片'));
        $form->number('price', __('商品价格'));
        $form->ckeditor('desc',__('商品信息'));
        return $form;
    }
}
