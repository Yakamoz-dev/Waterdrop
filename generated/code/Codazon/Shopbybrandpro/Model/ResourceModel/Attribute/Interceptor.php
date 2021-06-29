<?php
namespace Codazon\Shopbybrandpro\Model\ResourceModel\Attribute;

/**
 * Interceptor class for @see \Codazon\Shopbybrandpro\Model\ResourceModel\Attribute
 */
class Interceptor extends \Codazon\Shopbybrandpro\Model\ResourceModel\Attribute implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Eav\Model\ResourceModel\Entity\Type $eavEntityType, \Magento\Eav\Model\Config $eavConfig, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $eavEntityType, $eavConfig, $connectionName);
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
