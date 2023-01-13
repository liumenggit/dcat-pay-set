<?php

namespace Liumenggit\PaySet;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;

class PaySetServiceProvider extends ServiceProvider
{
    protected $js = [
        'js/index.js',
    ];
    protected $css = [
        'css/index.css',
    ];

    protected $img = [
        'img/ic-view-month.png',
    ];

    public function register()
    {
        //
    }

    public function init()
    {
        parent::init();
        Admin::requireAssets('@liumenggit.pay-set');
        //

    }

    protected $menu = [
        [
            'title' => '支付/交易',
            'uri' => '',
            'icon' => 'fa-cny',
        ],
        [
            'parent' => '支付/交易',
            'title' => '支付管理',
            'uri' => 'pay-set',
            'icon' => 'fa-align-justify', // 图标可以留空
        ],
        [
            'parent' => '支付/交易',
            'title' => '支付设置',
            'uri' => 'pay-test',
            'icon' => 'fa-cogs', // 图标可以留空
        ],
    ];

//	public function settingForm()
//	{
//		return new Setting($this);
//	}
}
