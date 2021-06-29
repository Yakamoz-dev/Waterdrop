<?php
namespace Magento\Customer\Model\Url;

/**
 * Interceptor class for @see \Magento\Customer\Model\Url
 */
class Interceptor extends \Magento\Customer\Model\Url implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Customer\Model\Session $customerSession, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\App\RequestInterface $request, \Magento\Framework\UrlInterface $urlBuilder, \Magento\Framework\Url\EncoderInterface $urlEncoder, ?\Magento\Framework\Url\DecoderInterface $urlDecoder = null, ?\Magento\Framework\Url\HostChecker $hostChecker = null)
    {
        $this->___init();
        parent::__construct($customerSession, $scopeConfig, $request, $urlBuilder, $urlEncoder, $urlDecoder, $hostChecker);
    }

    /**
     * {@inheritdoc}
     */
    public function getLoginPostUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLoginPostUrl');
        return $pluginInfo ? $this->___callPlugins('getLoginPostUrl', func_get_args(), $pluginInfo) : parent::getLoginPostUrl();
    }
}
