<?php
/**
 * Magedelight
 * Copyright (C) 2017 Magedelight <info@magedelight.com>
 *
 * @category Magedelight
 * @package Magedelight_Bundlediscount
 * @copyright Copyright (c) 2017 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */
namespace Magedelight\Bundlediscount\Block\Adminhtml;

class Tagwrapper extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    protected $_template = 'template/list.phtml';
    
    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->getToolbar()->addChild(
            'add_button',
            'Magento\Backend\Block\Widget\Button',
            [
                'label' => __('Add New Tagwrapper'),
                'onclick' => "window.location='" . $this->getCreateUrl() . "'",
                'class' => 'add primary add-template'
            ]
        );
        return parent::_prepareLayout();
    }

    /**
     * Get the url for create
     *
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new');
    }

    /**
     * Get header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __(' Tagwrappers');
    }
}
