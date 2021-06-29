<?php
namespace Codazon\MegaMenu\Controller\Adminhtml\Index\Export;

/**
 * Interceptor class for @see \Codazon\MegaMenu\Controller\Adminhtml\Index\Export
 */
class Interceptor extends \Codazon\MegaMenu\Controller\Adminhtml\Index\Export implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Setup\SampleData\FixtureManager $fixtureManager, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Magento\Framework\File\Csv $csv, \Codazon\MegaMenu\Model\MegamenuFactory $menuFactory)
    {
        $this->___init();
        parent::__construct($context, $fixtureManager, $resultForwardFactory, $csv, $menuFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute();
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }
}
