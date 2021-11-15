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
namespace Magedelight\Bundlediscount\Model\Theme;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    private $tagWrapper;

    /**
     * Constructor
     *
     * @param \Magedelight\Bundlediscount\Model\Tagwrapper $template
     */
    public function __construct(\Magedelight\Bundlediscount\Model\Tagwrapper $tagWrapper)
    {
        $this->tagWrapper =  $tagWrapper;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->tagWrapper->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
