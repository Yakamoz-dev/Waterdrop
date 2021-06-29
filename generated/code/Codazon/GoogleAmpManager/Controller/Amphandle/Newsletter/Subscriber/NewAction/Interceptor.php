<?php
namespace Codazon\GoogleAmpManager\Controller\Amphandle\Newsletter\Subscriber\NewAction;

/**
 * Interceptor class for @see \Codazon\GoogleAmpManager\Controller\Amphandle\Newsletter\Subscriber\NewAction
 */
class Interceptor extends \Codazon\GoogleAmpManager\Controller\Amphandle\Newsletter\Subscriber\NewAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory, \Magento\Customer\Model\Session $customerSession, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Customer\Model\Url $customerUrl, \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement, \Magento\Newsletter\Model\SubscriptionManagerInterface $subscriptionManager, ?\Magento\Framework\Validator\EmailAddress $emailValidator = null)
    {
        $this->___init();
        parent::__construct($context, $subscriberFactory, $customerSession, $storeManager, $customerUrl, $customerAccountManagement, $subscriptionManager, $emailValidator);
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
