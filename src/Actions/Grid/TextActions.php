<?php

namespace Liumenggit\PaySet\Actions\Grid;

use Dcat\Admin\Grid\Displayers\Actions;

/**
 * 重写行操作按钮样式
 */
class TextActions extends Actions
{

    /**
     * @return string
     */
    protected function getViewLabel()
    {
        $label = trans('admin.show');
        return '<i class="feather icon-eye text-success"></i> <span class="text-success">' . $label . '</span> &emsp;';
    }

    /**
     * @return string
     */
    protected function getEditLabel()
    {
        $label = trans('admin.edit');

        return '<i class="feather icon-edit-1 text-custom"></i> <span class="text-custom">' . $label . '</span> &emsp;';
    }

    /**
     * @return string
     */
    protected function getQuickEditLabel()
    {
        $label = trans('admin.edit');
        $label2 = trans('admin.quick_edit');

        return '<i class="feather icon-edit-1 text-custom"></i> <span class="text-custom" title="' . $label2 . '">' . $label . '</span> &emsp;';
    }

    /**
     * @return string
     */
    protected function getDeleteLabel()
    {
        $label = trans('admin.delete');

        return '<i class="feather icon-alert-triangle text-danger"></i> <span class="text-danger">' . $label . '</span> &emsp;';
    }
}
