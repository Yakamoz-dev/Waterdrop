<?php
 
namespace Magedelight\Bundlediscount\Controller\Index;
 
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Custom extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;
    protected $resultJsonFactory;

    /**
     * Custom constructor.
     * @param Context $context
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     * @param \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->priceHelper = $priceHelper;
        $this->bundlediscount = $bundlediscount;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $bundle = $this->getRequest()->getParam('bundle_id');
        $product = $this->getRequest()->getParam('product_option_id');

        $bundleModel = $this->bundlediscount->calculateDiscountAmountByProductId($bundle, $product);

        return $result->setData(['total_amount' => $this->priceHelper->currency(
            $bundleModel['total_amount'],
            true,
            false
        ),
            'discount_amount' => $this->priceHelper->currency(
                $bundleModel['discount_amount'],
                true,
                false
            ),
            'final_amount' => $this->priceHelper->currency(
                $bundleModel['final_amount'],
                true,
                false
            ),
            'discount_label' => $this->priceHelper->currency(
                $bundleModel['discount_label'],
                true,
                false
            )]);
    }
}
