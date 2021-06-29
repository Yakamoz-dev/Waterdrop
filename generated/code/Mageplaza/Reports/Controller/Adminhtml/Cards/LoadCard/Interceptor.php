<?php
namespace Mageplaza\Reports\Controller\Adminhtml\Cards\LoadCard;

/**
 * Interceptor class for @see \Mageplaza\Reports\Controller\Adminhtml\Cards\LoadCard
 */
class Interceptor extends \Mageplaza\Reports\Controller\Adminhtml\Cards\LoadCard implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Backend\Model\Auth\Session $authSession, \Mageplaza\Reports\Model\CardsManageFactory $cardsManageFactory)
    {
        $this->___init();
        parent::__construct($context, $authSession, $cardsManageFactory);
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
