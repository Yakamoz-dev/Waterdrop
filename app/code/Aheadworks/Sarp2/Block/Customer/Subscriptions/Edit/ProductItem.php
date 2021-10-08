<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Sarp2
 * @version    2.15.3
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\ProviderPool as ConfigProviderPool;
use Aheadworks\Sarp2\Model\Profile\Item\Checker\IsOneOffItem;
use Aheadworks\Sarp2\Model\Profile\Registry;
use Magento\Bundle\Model\Product\Type as BundleType;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View as ProductViewBlock;
use Magento\Catalog\Helper\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface;

/**
 * Class ProductItem
 *
 * @package Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit
 */
class ProductItem extends ProductViewBlock
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ConfigProviderPool
     */
    private $configProviderPool;

    /**
     * @var IsOneOffItem
     */
    private $isOneOffItem;

    /**
     * @param Context $context
     * @param EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param StringUtils $string
     * @param Product $productHelper
     * @param ConfigInterface $productTypeConfig
     * @param FormatInterface $localeFormat
     * @param Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param PriceCurrencyInterface $priceCurrency
     * @param Registry $registry
     * @param ConfigProviderPool $configProviderPool
     * @param IsOneOffItem $isOneOffItem
     * @param array $data
     */
    public function __construct(
        Context $context,
        EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        StringUtils $string,
        Product $productHelper,
        ConfigInterface $productTypeConfig,
        FormatInterface $localeFormat,
        Session $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency,
        Registry $registry,
        ConfigProviderPool $configProviderPool,
        IsOneOffItem $isOneOffItem,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
        $this->registry = $registry;
        $this->configProviderPool = $configProviderPool;
        $this->isOneOffItem = $isOneOffItem;
    }

    /**
     * Retrieve profile item
     *
     * @return ProfileItemInterface
     */
    public function getProfileItem()
    {
        return $this->registry->getProfileItem();
    }

    /**
     * Retrieve item qty
     *
     * @return int|null
     */
    public function getQty()
    {
        return $this->getProduct()->getPreconfiguredValues()->hasData('qty')
            ? $this->getProduct()->getPreconfiguredValues()->getData('qty')
            : null;
    }

    /**
     * Retrieve item configurable options
     *
     * @return array
     */
    public function getConfigurableOptions()
    {
        return $this->getProduct()->getPreconfiguredValues()->hasData('super_attribute')
            ? $this->getProduct()->getPreconfiguredValues()->getData('super_attribute')
            : [];
    }

    /**
     * Retrieve item configurable options as json
     * @return bool|false|string
     */
    public function getSerializedConfigurableOptions()
    {
        return $this->_jsonEncoder->encode($this->getConfigurableOptions());
    }

    /**
     * Get subscription config data
     *
     * @return bool|false|string
     */
    public function getSubscriptionConfigData()
    {
        try {
            $product = $this->getProduct();
            $productTypeId = $product->getTypeId();
            $item = $productTypeId != BundleType::TYPE_CODE
                ? $this->getProfileItem()
                : null;
            $configData = $this->configProviderPool->getConfigProvider($productTypeId)
                ->getConfig($product->getId(), $item);

            return $this->_jsonEncoder->encode($configData);
        } catch (\Exception $exception) {
            return $this->_jsonEncoder->encode([]);
        }
    }

    /**
     * Retrieve subscription option id
     *
     * @return int
     */
    public function getSubscriptionOptionId()
    {
        $item = $this->getProfileItem();
        if ($item instanceof ProfileItemInterface) {
            $options = $item->getProductOptions();
            return $options['info_buyRequest']['aw_sarp2_subscription_type'] ?? 0;
        }

        return 0;
    }

    /**
     * Check if current item is one-off
     *
     * @return bool
     */
    public function isOneOffItem(): bool
    {
        $item = $this->getProfileItem();

        return $this->isOneOffItem->check($item);
    }

    /**
     * @inheritDoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        $block = $this->getLayout()->getBlock('product.info');
        if ($block) {
            $block->setSubmitRouteData(
                [
                    'route' => 'aw_sarp2/profile_edit/saveItem',
                    'params' => [
                        'profile_id' => $this->getRequest()->getParam('profile_id'),
                        'item_id' => $this->getRequest()->getParam('item_id')
                    ],
                ]
            );
        }

        return parent::_prepareLayout();
    }
}
