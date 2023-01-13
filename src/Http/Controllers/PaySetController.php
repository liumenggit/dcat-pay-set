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
use Liumenggit\PaySet\Http\Repositories\PaySet;
use Psy\Util\Json;

use Liumenggit\PaySet\PaySetServiceProvider;


class PaySetController extends AdminController
{
    use HasUploadedFile;

    public function index(Content $content)
    {
        return $content
            ->title(PaySetServiceProvider::trans('pay-set.title'))
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
        Grid::resolving(function (Grid $grid) {
            $grid->tableCollapse(false);
        });
        return Grid::make(new PaySet(), function (Grid $grid) {

//            $grid->tools('<a class="btn btn-sm btn-default">设置支付方式</a>');

            $grid->disableBatchDelete(); //禁用批量删除
            $grid->setActionClass(TextActions::class); //操作按钮样式
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                //操作功能的禁用
//                $actions->disableDelete();
//                $actions->disableEdit();
                $actions->disableQuickEdit();
                $actions->disableView();
            });
            $grid->toolsWithOutline(false); //列表主题反向
            $grid->disableRowSelector(); //禁用行选择器
            $grid->disableFilterButton(); //禁用过滤器按钮
//            $grid->disableCreateButton(); //禁用创建按钮

//            $grid->disableEditButton();
//            $grid->showQuickEditButton();
            $grid->column('id')->sortable();
            $grid->column('name', '模板名称');
//            $grid->column('config', '配置信息');
            $grid->column('pay_type', '支付类型')->using([1 => '微信', 2 => '支付宝'])
                ->dot(
                    [
                        1 => 'success',
                        2 => 'danger',
                        3 => 'info',
                    ],
                );
            $grid->column('status', '状态')->using([0 => '关闭', 1 => '开启'])
                ->dot(
                    [
                        0 => 'danger',
                        1 => 'success',
                    ],
                );
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new PaySet(), function (Show $show) {
            $show->field('id');
            $show->field('name');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {

        return Form::make(new PaySet(), function (Form $form) {
//            $form->model()->pay_type;
//            $form->text('test', 'test')->value(json_encode($form->model()->config));
//            $form->text('test', 'test')->value($form->model()->config['w_type'] . '/' . $form->input('w_type'));
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

            $form->text('name', '标题')->required();
            $form->radio('pay_type', '支付方式类型')
                ->options([1 => '微信支付', 2 => '支付宝支付'])
                ->default($form->model()->pay_type ? $form->model()->pay_type : 1, true)
                ->help('支付方式保存完后不可修改，请谨慎操作')
                ->disable($form->model()->pay_type ? true : false)
                ->when(1, function (Form $form) {
                    $form->radio('w_port', '微信支付接口')
                        ->rules('required_if:pay_type,1')
                        ->options([1 => 'V2', 2 => 'V3'])
                        ->default($form->model()->config ? $form->model()->config['w_port'] : 1, true)
                        ->when([1, 2], function (Form $form) {
                            $form->text('w_appid', 'AppId')
                                ->rules('required_if:pay_type,1')->minLength(18, '最少输入18个字符')
                                ->setLabelClass(['asterisk'])
                                ->help('用于公众号的支付方式请填写微信公众号支付appid;用于小程序的支付方式请填写微信小程序支付appid')
                                ->customFormat(function () {
                                    return key_exists('w_appid', $this->config) ? $this->config['w_appid'] : null;
                                });
                            $form->text('w_mchid', '商户号')
                                ->rules('required_if:pay_type,1')->minLength(10, '最少输入10个字符')
                                ->setLabelClass(['asterisk'])
                                ->customFormat(function () {
                                    return key_exists('w_mchid', $this->config) ? $this->config['w_mchid'] : null;
                                });
                        })
                        ->when(2, function (Form $form) {
                            $form->text('w_serial_no', '证书序列号')
                                ->rules('required_if:w_port,2')->minLength(40, '最少输入40个字符')
                                ->setLabelClass(['asterisk'])
                                ->help('微信支付商户平台如何升级V3版支付')
                                ->customFormat(function () {
                                    return key_exists('w_serial_no', $this->config) ? $this->config['w_serial_no'] : null;
                                });
                            $form->text('w_v3', 'V3支付密钥')
                                ->rules('required_if:w_port,2')->minLength(32, '最少输入32个字符')
                                ->setLabelClass(['asterisk'])
                                ->help('微信支付商户平台如何升级V3版支付')
                                ->customFormat(function () {
                                    return key_exists('w_v3', $this->config) ? $this->config['w_v3'] : null;
                                });
                        });
                    $form->radio('w_type', '商户类型')
                        ->options([1 => '普通商户', 2 => '子商户'])
                        ->default($form->model()->config ? $form->model()->config['w_type'] : 1, true)
                        ->when([1, 2], function (Form $form) {
                            //商户
                            $form->text('w_pay_key', '支付密钥')
                                ->rules('required_if:pay_type,1')->minLength(32, '最少输入32个字符')
                                ->setLabelClass(['asterisk'])
                                ->customFormat(function () {
                                    return key_exists('w_pay_key', $this->config) ? $this->config['w_pay_key'] : null;
                                });
                            $form->file('w_serial_pem', '商户证书')
                                ->autoUpload()
                                ->saveFullUrl()
                                ->rules('required_if:pay_type,1')
                                ->setLabelClass(['asterisk'])
                                ->accept('pem')
                                ->help('请选择名称为apiclient_cert.pem的证书文件<a href="http://www.w3school.com.cn" target="view_frame">W3School</a>')
                                ->uniqueName()
                                ->customFormat(function () {
                                    return key_exists('w_serial_pem', $this->config) ? $this->config['w_serial_pem'] : null;
                                });
                            $form->file('w_serial_key', '商户KEY证书')
                                ->autoUpload()
                                ->saveFullUrl()
                                ->rules('required_if:pay_type,1')
                                ->setLabelClass(['asterisk'])
                                ->accept('pem')
                                ->help('请选择名称为apiclient_cert.pem的证书文件<a href="http://www.w3school.com.cn" target="view_frame">W3School</a>')
                                ->uniqueName()
                                ->customFormat(function () {
                                    return key_exists('w_serial_key', $this->config) ? $this->config['w_serial_pem'] : null;
                                });;
                        })
                        ->when(2, function (Form $form) {
                            //子商户
                            $form->text('w_sub_appid', '子商户AppId')
                                ->rules('required_if:w_type,2')
                                ->setLabelClass(['asterisk'])
                                ->customFormat(function () {
                                    return key_exists('w_sub_appid', $this->config) ? $this->config['w_sub_appid'] : null;
                                });
                            $form->text('w_sub_mchid', '子商户号')
                                ->rules('required_if:w_type,2')
                                ->setLabelClass(['asterisk'])
                                ->customFormat(function () {
                                    return key_exists('w_sub_mchid', $this->config) ? $this->config['w_sub_mchid'] : null;
                                });
                            $form->text('w_sub_pay_key', '子商户支付密钥')
                                ->rules('required_if:w_type,2')
                                ->setLabelClass(['asterisk'])
                                ->customFormat(function () {
                                    return key_exists('w_sub_pay_key', $this->config) ? $this->config['w_sub_pay_key'] : null;
                                });
                            $form->file('w_sub_serial_pem', '子商户证书')
                                ->autoUpload()
                                ->saveFullUrl()
                                ->rules('required_if:w_type,2')
                                ->setLabelClass(['asterisk'])
                                ->accept('pem')
                                ->uniqueName()
                                ->customFormat(function () {
                                    return key_exists('w_sub_serial_pem', $this->config) ? $this->config['w_sub_serial_pem'] : null;
                                });;
                            $form->file('w_sub_serial_key', '子商户key证书')
                                ->autoUpload()
                                ->saveFullUrl()
                                ->rules('required_if:w_type,2')
                                ->setLabelClass(['asterisk'])
                                ->accept('pem')
                                ->uniqueName()
                                ->customFormat(function () {
                                    return key_exists('w_sub_serial_key', $this->config) ? $this->config['w_sub_serial_key'] : null;
                                });;

                        });
                })
                ->when(2, function (Form $form) {
                    //支付宝
                });
            $form->switch('status', '状态');

            $form->submitted(function (Form $form) {
//                $username = $form->pay_type;
//                $form->responseValidationMessages('titkle', '测试' .);
//                dd($form->input());
//                dd($form->model());
                $form->hidden('config');
                $form->pay_type = $form->model()->pay_type ? $form->model()->pay_type : $form->pay_type;
                if ($form->pay_type == 1 || $form->model()->pay_type == 1) {
                    //微信
                    $form->config = [
                        'w_port' => $form->w_port ? $form->w_port : $form->model()->config['w_port'],
                        'w_appid' => $form->w_appid ? $form->w_appid : $form->model()->config['w_appid'],
                        'w_mchid' => $form->w_mchid ? $form->w_mchid : $form->model()->config['w_mchid'],
                        'w_serial_no' => $form->w_serial_no ? $form->w_serial_no : ($form->model()->config ? $form->model()->config['w_serial_no'] : null),
                        'w_type' => $form->w_type ? $form->w_type : ($form->model()->config ? $form->model()->config['w_type'] : null),
                        'w_v3' => $form->w_v3 ? $form->w_v3 : ($form->model()->config ? $form->model()->config['w_v3'] : null),
                        'w_pay_key' => $form->w_pay_key ? $form->w_pay_key : ($form->model()->config ? $form->model()->config['w_pay_key'] : null),
                        'w_serial_pem' => $form->w_serial_pem ? $form->w_serial_pem : ($form->model()->config ? $form->model()->config['w_serial_pem'] : null),
                        'w_sub_appid' => $form->w_sub_appid ? $form->w_sub_appid : ($form->model()->config ? $form->model()->config['w_sub_appid'] : null),
                        'w_sub_mchid' => $form->w_sub_mchid ? $form->w_sub_mchid : ($form->model()->config ? $form->model()->config['w_sub_mchid'] : null),
                        'w_sub_pay_key' => $form->w_sub_pay_key ? $form->w_sub_pay_key : ($form->model()->config ? $form->model()->config['w_sub_pay_key'] : null),
                        'w_sub_serial_pem' => $form->w_sub_serial_pem ? $form->w_sub_serial_pem : ($form->model()->config ? $form->model()->config['w_sub_serial_pem'] : null),
                        'w_sub_serial_key' => $form->w_sub_serial_key ? $form->w_sub_serial_key : ($form->model()->config ? $form->model()->config['w_sub_serial_key'] : null),
                        'w_serial_key' => $form->w_serial_key ? $form->w_serial_key : ($form->model()->config ? $form->model()->config['w_serial_key'] : null),
                    ];
                } elseif ($form->pay_type == 2 || $form->model()->pay_type == 2) {
                    //支付宝
                    $form->config = [];
                }

//                dd($form->config);

                $form->ignore([
                    'test',
                    'w_port',
                    'w_appid',
                    'w_mchid',
                    'w_serial_no',
                    'w_v3',
                    'w_type',
                    'w_pay_key',
                    'w_serial_pem',
                    'w_serial_key',
                    'w_sub_appid',
                    'w_sub_mchid',
                    'w_sub_pay_key',
                    'w_sub_serial_pem',
                    'w_sub_serial_key'
                ]);
            });
//            $form->confirm('您确定要提交表单吗？',  json_decode($form->config));
        });
    }
}
