<?php
namespace Codazon\ThemeOptions\Model\ResourceModel\Config\Collection\Scoped;

/**
 * Interceptor class for @see \Codazon\ThemeOptions\Model\ResourceModel\Config\Collection\Scoped
 */
class Interceptor extends \Codazon\ThemeOptions\Model\ResourceModel\Config\Collection\Scoped implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config $scopeConfig, \Magento\Theme\Model\Design $design, \Magento\Framework\Data\Collection\EntityFactory $entityFactory, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy, \Magento\Framework\Event\ManagerInterface $eventManager, \Codazon\ThemeOptions\Model\ResourceModel\Config\Data $resource, \Magento\Theme\Model\ResourceModel\Theme\Collection $themeCollection, $scope, ?\Magento\Framework\DB\Adapter\AdapterInterface $connection = null, $scopeId = null)
    {
        $this->___init();
        parent::__construct($scopeConfig, $design, $entityFactory, $logger, $fetchStrategy, $eventManager, $resource, $themeCollection, $scope, $connection, $scopeId);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurPage($displacement = 0)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCurPage');
        return $pluginInfo ? $this->___callPlugins('getCurPage', func_get_args(), $pluginInfo) : parent::getCurPage($displacement);
    }
}
