<?php

namespace Liumenggit\PaySet\Http\Controllers;

use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Support\Helper;
use Dcat\Admin\Traits\HasUploadedFile;
use Liumenggit\PaySet\Actions\Grid\TextActions;
use Liumenggit\PaySet\Models\PaySet;
use Liumenggit\PaySet\Models\PayTree;
use Liumenggit\PaySet\PaySetServiceProvider;
use Psy\Util\Json;

//use App\Admin\Repositories\FormDesign;

class PayTestController extends AdminController
{
    public function index(Content $content)
    {
        return $content
            ->title('支付设置')
            ->description(trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
//        Grid::resolving(function (Grid $grid) {
//            $grid->tableCollapse(false);
//        });
        return Grid::make(new PayTree(['template']), function (Grid $grid) {
//            $grid->id('ID')->bold()->sortable();
            $grid->toolsWithOutline(false); //列表主题反向
            $grid->setActionClass(TextActions::class); //操作按钮样式
            $grid->disableRowSelector(); //禁用行选择器
            $grid->disableFilterButton(); //禁用过滤器按钮
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                //操作功能的禁用
                $actions->disableDelete();
//                $actions->disableEdit();
//                $actions->disableQuickEdit();
                $actions->disableView();
            });
            $grid->tools('<a class="btn btn-primary grid-refresh btn-mini" style="color: white" href="/admin/pay-set/create">新建模板</a>');

            $grid->showColumnSelector();//设置列选择器 (字段显示或隐藏 showColumnSelector)
            $grid->hideColumns(['pay_set_id', 'mark']);
            $grid->column('title', '支付类型')->tree(true)
                ->if(function ($column) {
                    return (bool)$this->parent_id;
                })
                ->display(function ($title) {
                    return preg_replace('/<i[^>]*(.*?)<\/i>/i', '<img src="' . $this->icon . '" style="height:25px;width:25px;"/>', $title);
                });
            // 开启树状表格功能
            $grid->column('order', '支付顺序')->if(function () {
                return (bool)$this->parent_id;
            })->orderable()->else()->display('');
            $grid->column('template.name', '当前模板')->link(function ($value) {
                return admin_url('/pay-set/' . PayTree::find($this->getKey())->pay_set_id . '/edit');
            });

            $grid->column('pay_set_id', '模板')
                ->select(function () {
                    return PaySet::where('pay_type', PayTree::find($this->getKey(), ['pay_type_id'])->pay_type_id)->pluck('name', 'id')->put(0, '不启用');
                }, true)
//                ->setAttributes(['style' => 'background-color: aqua'])
                ->if(function ($column) {
                    if (!$this->parent_id) {
                        $column->setAttributes(['style' => 'display: table-column;']);
                    }
                    return false;
                });
//            $grid->column('template.status', '模板状态')
//                ->bool(['Y' => true, 'N' => false])
//                ->if(function ($column) {
//                    if (!$this->parent_id) {
//                        $column->setAttributes(['style' => 'display: table-column;']);
//                    }
//                });
//                ->display(function ($title) {
//                    return 'asasdad' . $title;
//                });
            $grid->column('mark', '标识符');
//            $grid->column('status', '状态')->switch();

//            $grid->created_at;
//            $grid->updated_at;

        });
    }

    protected function form()
    {

        return Form::make(new PayTree(), function (Form $form) {
            $form->footer(function ($footer) {
                // 去掉`重置`按钮
                $footer->disableReset();
                // 去掉`查看`checkbox
                $footer->disableViewCheck();
                // 去掉`继续编辑`checkbox
                $footer->disableEditingCheck();
                // 去掉`继续创建`checkbox
                $footer->disableCreatingCheck();
            });

            $form->tools(function (Form\Tools $tools) {
                // 去掉跳转列表按钮
                $tools->disableList();
                // 去掉跳转详情页按钮
                $tools->disableView();
                // 去掉删除按钮
                $tools->disableDelete();
            });

            $names = PayTree::where('parent_id', 0)->pluck('title', 'id')->put(0, '顶级');
            $form->text('title', '标题');
            $form->text('mark', '标识符');
            $form->hidden('pay_set_id');
            $form->hidden('status');
            $form->select('parent_id', '层级')
//                ->disable(!$form->model()->parent_id && !$form->parent_id)
                ->options($names)
                ->default(0)
                ->when('>', 0, function (Form $form) {

                    $form->image('icon', '图标')->autoUpload()->saveFullUrl();
//                    $form->icon('icon');
                    $form->select('pay_type_id', '支付类型')->options([1 => '微信', 2 => '支付宝', 3 => 'PayPal'])->default(1);
                });


        });
    }
}
