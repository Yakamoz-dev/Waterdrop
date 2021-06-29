<?php
namespace Ecopure\Gift\Model;
class Address extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'ecopure_gift_address';

    protected $_cacheTag = 'ecopure_gift_address';

    protected $_eventPrefix = 'ecopure_gift_address';

    protected function _construct()
    {
        $this->_init('Ecopure\Gift\Model\ResourceModel\Address');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    public function isActive()
    {
        return true;
    }
}
