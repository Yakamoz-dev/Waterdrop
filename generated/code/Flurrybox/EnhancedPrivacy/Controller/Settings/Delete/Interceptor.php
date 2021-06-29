<?php
namespace Flurrybox\EnhancedPrivacy\Controller\Settings\Delete;

/**
 * Interceptor class for @see \Flurrybox\EnhancedPrivacy\Controller\Settings\Delete
 */
class Interceptor extends \Flurrybox\EnhancedPrivacy\Controller\Settings\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Flurrybox\EnhancedPrivacy\Helper\Data $privacyHelper, \Magento\Customer\Model\Session $customerSession, \Flurrybox\EnhancedPrivacy\Api\CustomerManagementInterface $customerManagement)
    {
        $this->___init();
        parent::__construct($context, $privacyHelper, $customerSession, $customerManagement);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute();
    }
}
