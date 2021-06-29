<?php
namespace Codazon\Lookbookpro\Model\ResourceModel\LookbookCategoryAttribute;

/**
 * Interceptor class for @see \Codazon\Lookbookpro\Model\ResourceModel\LookbookCategoryAttribute
 */
class Interceptor extends \Codazon\Lookbookpro\Model\ResourceModel\LookbookCategoryAttribute implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Eav\Model\ResourceModel\Entity\Type $eavEntityType, \Magento\Eav\Model\Config $eavConfig, \Magento\Catalog\Model\Attribute\LockValidatorInterface $lockValidator, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $eavEntityType, $eavConfig, $lockValidator, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreLabelsByAttributeId($attributeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreLabelsByAttributeId');
        return $pluginInfo ? $this->___callPlugins('getStoreLabelsByAttributeId', func_get_args(), $pluginInfo) : parent::getStoreLabelsByAttributeId($attributeId);
    }
}
