<?php
namespace Flurrybox\EnhancedPrivacy\Controller\Settings\Export;

/**
 * Interceptor class for @see \Flurrybox\EnhancedPrivacy\Controller\Settings\Export
 */
class Interceptor extends \Flurrybox\EnhancedPrivacy\Controller\Settings\Export implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Flurrybox\EnhancedPrivacy\Helper\Data $privacyHelper, \Magento\Customer\Model\Session $customerSession, \Flurrybox\EnhancedPrivacy\Api\ProcessorsInterface $processors, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime, \Magento\Framework\File\Csv $csvWriter, \Magento\Framework\Archive\Zip $zip, \Magento\Framework\Filesystem\Driver\File $file)
    {
        $this->___init();
        parent::__construct($context, $privacyHelper, $customerSession, $processors, $fileFactory, $dateTime, $csvWriter, $zip, $file);
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
