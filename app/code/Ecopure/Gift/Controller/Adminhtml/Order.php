<?php
/**
 * Sample_News extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Sample
 * @package   Sample_News
 * @copyright 2016 Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru
 */
namespace Ecopure\Gift\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

abstract class Order extends Action
{
    /**
     * @var string
     */
    const ACTION_RESOURCE = 'Ecopure_Gift::order';
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Registry $registry
     * @param PageFactory $resultPageFactory
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        PageFactory $resultPageFactory,
        Context $context
    ) {
        $this->coreRegistry      = $registry;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
}
