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

class Category implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Ashsmith\Blog\Model\Post
     */
    protected $_template;

    /**
     * Constructor
     *
     * @param \Magedelight\Bundlediscount\Model\Tagwrapper $template
     */
    public function __construct(\Magedelight\Bundlediscount\Model\Tagcategories $template)
    {
        $this->_template =  $template;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $categories[] = ['label' => 'Not Assigned', 'value' => ''];
        /*$categories[] = ['label' => 'Category Deleted', 'value' => 'deleted'];*/
        $availableCategories = $this->_template->getAvailableCategories();
        foreach ($availableCategories as $key => $value) {
            $categories[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $categories;
    }
}
