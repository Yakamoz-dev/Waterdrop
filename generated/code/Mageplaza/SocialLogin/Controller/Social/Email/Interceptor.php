<?php
namespace Mageplaza\SocialLogin\Controller\Social\Email;

/**
 * Interceptor class for @see \Mageplaza\SocialLogin\Controller\Social\Email
 */
class Interceptor extends \Mageplaza\SocialLogin\Controller\Social\Email implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Customer\Api\AccountManagementInterface $accountManager, \Mageplaza\SocialLogin\Helper\Social $apiHelper, \Mageplaza\SocialLogin\Model\Social $apiObject, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Model\Account\Redirect $accountRedirect, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Customer\Model\Customer $customerModel, \Magento\Customer\Model\CustomerFactory $customerFactory, \Magento\Framework\Encryption\EncryptorInterface $encrypt, \Magento\Customer\Api\CustomerRepositoryInterface $_customerRepositoryInterface, \Magento\Customer\Model\CustomerRegistry $_customerRegistry)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $accountManager, $apiHelper, $apiObject, $customerSession, $accountRedirect, $resultRawFactory, $resultJsonFactory, $customerModel, $customerFactory, $encrypt, $_customerRepositoryInterface, $_customerRegistry);
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
