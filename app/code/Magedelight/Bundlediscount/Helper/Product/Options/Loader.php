<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magedelight\Bundlediscount\Helper\Product\Options;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\ConfigurableProduct\Api\Data\OptionInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\ConfigurableProduct\Api\Data\OptionValueInterfaceFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;

class Loader extends \Magento\ConfigurableProduct\Helper\Product\Options\Loader
{
    /**
     * @var OptionValueInterfaceFactory
     */
    private $optionValueFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * ReadHandler constructor
     *
     * @param OptionValueInterfaceFactory $optionValueFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        OptionValueInterfaceFactory $optionValueFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {
        $this->optionValueFactory = $optionValueFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * @param ProductInterface $product
     * @return OptionInterface[]
     */
    public function load(ProductInterface $product)
    {
        $options = [];
        /** @var Configurable $typeInstance */
        $typeInstance = $product->getTypeInstance();
        if (get_class($typeInstance) == 'Magento\Catalog\Model\Product\Type\Simple' || get_class($typeInstance) == 'Magento\Bundle\Model\Product\Type') {
            return false;
        }
//        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
//        $logger = new \Zend\Log\Logger();
//        $logger->addWriter($writer);
//        $logger->info(get_class($typeInstance));
        if (get_class($typeInstance) == 'Magento\ConfigurableProduct\Model\Product\Type\Configurable') {
            $attributeCollection = $typeInstance->getConfigurableAttributeCollection($product);
            //getConfigurableAttributeCollection
            $this->extensionAttributesJoinProcessor->process($attributeCollection);
            foreach ($attributeCollection as $attribute) {
                $values = [];
                $attributeOptions = $attribute->getOptions();
                if (is_array($attributeOptions)) {
                    foreach ($attributeOptions as $option) {
                        /** @var \Magento\ConfigurableProduct\Api\Data\OptionValueInterface $value */
                        $value = $this->optionValueFactory->create();
                        $value->setValueIndex($option['value_index']);
                        $values[] = $value;
                    }
                }
                $attribute->setValues($values);
                $options[] = $attribute;
            }

            return $options;
        }
    }
}
