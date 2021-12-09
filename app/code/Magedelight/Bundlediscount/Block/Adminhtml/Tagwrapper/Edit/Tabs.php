<?php

namespace Magedelight\Bundlediscount\Block\Adminhtml\Tagwrapper\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('tagwrapper_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Tagwrapper Details'));
    }
}
