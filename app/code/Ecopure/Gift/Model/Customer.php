<?php
namespace Ecopure\Gift\Model;
class Customer extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'ecopure_gift_customer';

    protected $_cacheTag = 'ecopure_gift_customer';

    protected $_eventPrefix = 'ecopure_gift_customer';

    protected function _construct()
    {
        $this->_init('Ecopure\Gift\Model\ResourceModel\Customer');
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
