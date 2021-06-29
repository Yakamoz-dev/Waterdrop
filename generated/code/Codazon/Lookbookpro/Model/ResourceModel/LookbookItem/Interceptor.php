<?php
namespace Codazon\Lookbookpro\Model\ResourceModel\LookbookItem;

/**
 * Interceptor class for @see \Codazon\Lookbookpro\Model\ResourceModel\LookbookItem
 */
class Interceptor extends \Codazon\Lookbookpro\Model\ResourceModel\LookbookItem implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Eav\Model\Entity\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Codazon\Lookbookpro\Model\LookbookItemFactory $modelFactory, $data = [])
    {
        $this->___init();
        parent::__construct($context, $storeManager, $modelFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Magento\Framework\Model\AbstractModel $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        return $pluginInfo ? $this->___callPlugins('save', func_get_args(), $pluginInfo) : parent::save($object);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        return $pluginInfo ? $this->___callPlugins('delete', func_get_args(), $pluginInfo) : parent::delete($object);
    }
}
