<?php
namespace OlegKoval\RegenerateUrlRewrites\Console\Command\RegenerateUrlRewrites;

/**
 * Interceptor class for @see \OlegKoval\RegenerateUrlRewrites\Console\Command\RegenerateUrlRewrites
 */
class Interceptor extends \OlegKoval\RegenerateUrlRewrites\Console\Command\RegenerateUrlRewrites implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\ResourceConnection $resource, \Magento\Framework\App\State\Proxy $appState, \Magento\Store\Model\StoreManagerInterface $storeManager, \OlegKoval\RegenerateUrlRewrites\Helper\Regenerate $helper, \OlegKoval\RegenerateUrlRewrites\Model\RegenerateCategoryRewrites $regenerateCategoryRewrites, \OlegKoval\RegenerateUrlRewrites\Model\RegenerateProductRewrites $regenerateProductRewrites)
    {
        $this->___init();
        parent::__construct($resource, $appState, $storeManager, $helper, $regenerateCategoryRewrites, $regenerateProductRewrites);
    }

    /**
     * {@inheritdoc}
     */
    public function run(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'run');
        return $pluginInfo ? $this->___callPlugins('run', func_get_args(), $pluginInfo) : parent::run($input, $output);
    }
}
