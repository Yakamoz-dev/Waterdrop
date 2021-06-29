<?php
namespace Magento\Framework\View\Asset\File\FallbackContextFactory;

/**
 * Interceptor class for @see \Magento\Framework\View\Asset\File\FallbackContextFactory
 */
class Interceptor extends \Magento\Framework\View\Asset\File\FallbackContextFactory implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->___init();
        parent::__construct($objectManager);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'create');
        return $pluginInfo ? $this->___callPlugins('create', func_get_args(), $pluginInfo) : parent::create($data);
    }
}
