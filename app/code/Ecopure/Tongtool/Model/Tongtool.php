<?php
namespace Ecopure\Tongtool\Model;
class Tongtool extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'ecopure_tongtool';

    protected $_cacheTag = 'ecopure_tongtool';

    protected $_eventPrefix = 'ecopure_tongtool';

    protected function _construct()
    {
        $this->_init('Ecopure\Tongtool\Model\ResourceModel\Tongtool');
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
