<?php
namespace Magento\Framework\App\View;

/**
 * Interceptor class for @see \Magento\Framework\App\View
 */
class Interceptor extends \Magento\Framework\App\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\LayoutInterface $layout, \Magento\Framework\App\RequestInterface $request, \Magento\Framework\App\ResponseInterface $response, \Magento\Framework\Config\ScopeInterface $configScope, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Framework\View\Result\PageFactory $pageFactory, \Magento\Framework\App\ActionFlag $actionFlag)
    {
        $this->___init();
        parent::__construct($layout, $request, $response, $configScope, $eventManager, $pageFactory, $actionFlag);
    }

    /**
     * {@inheritdoc}
     */
    public function generateLayoutBlocks()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'generateLayoutBlocks');
        return $pluginInfo ? $this->___callPlugins('generateLayoutBlocks', func_get_args(), $pluginInfo) : parent::generateLayoutBlocks();
    }
}
