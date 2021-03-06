<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Reports
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Reports\Block;

use Magento\Backend\Block\Template;
use Mageplaza\Reports\Helper\Data;

/**
 * Class SwitchToNew
 * @package Mageplaza\Reports\Block
 */
class SwitchToNew extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Mageplaza_Reports::switch.phtml';

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * SwitchToNew constructor.
     *
     * @param Template\Context $context
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Data $helperData,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->_helperData = $helperData;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->canShow()) {
            return '';
        }

        return parent::_toHtml(); // TODO: Change the autogenerated stub
    }

    /**
     * @return bool
     */
    private function canShow()
    {
        return $this->_helperData->getConfigGeneral('shownof') && !$this->_helperData->isEnabled();
    }
}
