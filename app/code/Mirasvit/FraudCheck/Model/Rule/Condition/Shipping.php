<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-fraud-check
 * @version   1.1.5
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\FraudCheck\Model\Rule\Condition;

use Magento\Customer\Model\AddressFactory;
use Magento\Directory\Model\Config\Source\Country as CountrySource;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Rule\Model\Condition\Context;
use Magento\Shipping\Model\Config as ShippingConfig;

/**
 * @method string getAttribute()
 * @method $this setAttributeOption($attributes)
 * @method array getAttributeOption()
 */
class Shipping extends AbstractCondition
{
    /**
     * @var AddressFactory
     */
    protected $addressFactory;

    /**
     * @var ShippingConfig
     */
    protected $shippingConfig;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var CountrySource
     */
    protected $countrySource;

    /**
     * Shipping constructor.
     * @param AddressFactory $addressFactory
     * @param ShippingConfig $shippingConfig
     * @param ScopeConfigInterface $scopeConfig
     * @param CountrySource $countrySource
     * @param Context $context
     */
    public function __construct(
        AddressFactory $addressFactory,
        ShippingConfig $shippingConfig,
        ScopeConfigInterface $scopeConfig,
        CountrySource $countrySource,
        Context $context
    ) {
        $this->addressFactory = $addressFactory;
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig    = $scopeConfig;
        $this->countrySource  = $countrySource;

        return parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function loadAttributeOptions()
    {
        $attributes = [
            'shipping_method' => __('Shipping: Shipping Method'),
        ];

        $addressAttributes = $this->addressFactory->create()->getAttributes();
        foreach ($addressAttributes as $attr) {
            if ($attr->getStoreLabel() && $attr->getAttributeCode()) {
                $attributes[$attr->getAttributeCode()] = __('Shipping: ') . $attr->getStoreLabel();
            }
        }

        $this->setAttributeOption($attributes);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputType()
    {
        switch ($this->getAttribute()) {
            case 'shipping_method':
            case 'country_id':
                $type = 'multiselect';
                break;

            default:
                $type = 'string';
        }

        return $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getValueElementType()
    {
        switch ($this->getAttribute()) {
            case 'shipping_method':
            case 'country_id':
                $type = 'multiselect';
                break;
            default:
                $type = 'text';
        }

        return $type;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function getValueOption()
    {
        $options = [];

        if ($this->getAttribute() === 'shipping_method') {
            /** @var \Magento\Shipping\Model\Carrier\CarrierInterface $carrier */
            foreach ($this->shippingConfig->getAllCarriers() as $code => $carrier) {
                $title          = $this->scopeConfig->getValue("carriers/$code/title");
                $options[$code] = $title;

                $methods = $carrier->getAllowedMethods();
                if (!$methods) {
                    continue;
                }

                foreach ($methods as $methodCode => $methodTitle) {
                    $value = $label = $code . '_' . $methodCode;
                    if (!empty($methodTitle)) {
                        $label = '·····[' . $code . '] ' . $methodTitle;
                    }
                    $options[$value] = $label;
                }
            }
        } elseif ($this->getAttribute() === 'country_id') {
            foreach ($this->countrySource->toOptionArray(true) as $country) {
                $options[$country['value']] = $country['label'];
            }
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(AbstractModel $object)
    {
        $attrCode = $this->getAttribute();

        /** @var \Magento\Sales\Model\Order $object */
        $shippingAddress = $object->getShippingAddress();

        if ($attrCode === 'shipping_method') {
            $value = $object->getData('shipping_method');
        } elseif ($shippingAddress) {
            $value = $shippingAddress->getData($attrCode);
        } else {
            $value = false;
        }

        return parent::validateAttribute($value);
    }
}
