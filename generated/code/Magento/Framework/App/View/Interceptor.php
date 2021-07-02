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
    public function getPage()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPage');
        return $pluginInfo ? $this->___callPlugins('getPage', func_get_args(), $pluginInfo) : parent::getPage();
    }

    /**
     * {@inheritdoc}
     */
    public function getLayout()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLayout');
        return $pluginInfo ? $this->___callPlugins('getLayout', func_get_args(), $pluginInfo) : parent::getLayout();
    }

    /**
     * {@inheritdoc}
     */
    public function loadLayout($handles = null, $generateBlocks = true, $generateXml = true, $addActionHandles = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadLayout');
        return $pluginInfo ? $this->___callPlugins('loadLayout', func_get_args(), $pluginInfo) : parent::loadLayout($handles, $generateBlocks, $generateXml, $addActionHandles);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLayoutHandle()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDefaultLayoutHandle');
        return $pluginInfo ? $this->___callPlugins('getDefaultLayoutHandle', func_get_args(), $pluginInfo) : parent::getDefaultLayoutHandle();
    }

    /**
     * {@inheritdoc}
     */
    public function addActionLayoutHandles()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addActionLayoutHandles');
        return $pluginInfo ? $this->___callPlugins('addActionLayoutHandles', func_get_args(), $pluginInfo) : parent::addActionLayoutHandles();
    }

    /**
     * {@inheritdoc}
     */
    public function addPageLayoutHandles(array $parameters = [], $defaultHandle = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addPageLayoutHandles');
        return $pluginInfo ? $this->___callPlugins('addPageLayoutHandles', func_get_args(), $pluginInfo) : parent::addPageLayoutHandles($parameters, $defaultHandle);
    }

    /**
     * {@inheritdoc}
     */
    public function loadLayoutUpdates()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadLayoutUpdates');
        return $pluginInfo ? $this->___callPlugins('loadLayoutUpdates', func_get_args(), $pluginInfo) : parent::loadLayoutUpdates();
    }

    /**
     * {@inheritdoc}
     */
    public function generateLayoutXml()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'generateLayoutXml');
        return $pluginInfo ? $this->___callPlugins('generateLayoutXml', func_get_args(), $pluginInfo) : parent::generateLayoutXml();
    }

    /**
     * {@inheritdoc}
     */
    public function generateLayoutBlocks()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'generateLayoutBlocks');
        return $pluginInfo ? $this->___callPlugins('generateLayoutBlocks', func_get_args(), $pluginInfo) : parent::generateLayoutBlocks();
    }

    /**
     * {@inheritdoc}
     */
    public function renderLayout($output = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'renderLayout');
        return $pluginInfo ? $this->___callPlugins('renderLayout', func_get_args(), $pluginInfo) : parent::renderLayout($output);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsLayoutLoaded($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setIsLayoutLoaded');
        return $pluginInfo ? $this->___callPlugins('setIsLayoutLoaded', func_get_args(), $pluginInfo) : parent::setIsLayoutLoaded($value);
    }

    /**
     * {@inheritdoc}
     */
    public function isLayoutLoaded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isLayoutLoaded');
        return $pluginInfo ? $this->___callPlugins('isLayoutLoaded', func_get_args(), $pluginInfo) : parent::isLayoutLoaded();
    }
}
