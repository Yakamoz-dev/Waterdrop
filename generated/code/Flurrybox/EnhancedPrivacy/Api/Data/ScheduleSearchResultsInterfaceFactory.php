<?php
namespace Flurrybox\EnhancedPrivacy\Api\Data;

/**
 * Factory class for @see \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleSearchResultsInterface
 */
class ScheduleSearchResultsInterfaceFactory
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Flurrybox\\EnhancedPrivacy\\Api\\Data\\ScheduleSearchResultsInterface')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleSearchResultsInterface
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
