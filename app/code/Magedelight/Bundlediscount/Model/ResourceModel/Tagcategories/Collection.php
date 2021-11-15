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

// @codingStandardsIgnoreFile

/**
 * Directory Gift Categories Resource Collection
 */
namespace Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    
    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magedelight\Bundlediscount\Model\Tagcategories',
            'Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories');
    }
}
