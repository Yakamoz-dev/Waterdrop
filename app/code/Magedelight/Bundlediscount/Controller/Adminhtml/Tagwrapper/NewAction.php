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
namespace Magedelight\Bundlediscount\Controller\Adminhtml\Tagwrapper;

class NewAction extends \Magedelight\Bundlediscount\Controller\Adminhtml\Tagwrapper
{
    /**
     * Create new Newsletter Bundlediscount
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
