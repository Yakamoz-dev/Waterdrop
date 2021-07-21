<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Sarp2
 * @version    2.15.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Model\ResourceModel\Profile\Address;

use Aheadworks\Sarp2\Model\Profile;
use Aheadworks\Sarp2\Model\Profile\Address;
use Aheadworks\Sarp2\Model\ResourceModel\Profile\Address as AddressResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Aheadworks\Sarp2\Model\ResourceModel\Profile\Address
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected $_idFieldName = 'address_id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Address::class, AddressResource::class);
    }

    /**
     * Add profile filter
     *
     * @param Profile $profile
     * @return $this
     */
    public function addProfileFilter(Profile $profile)
    {
        $profileId = $profile->getProfileId();
        if ($profileId) {
            $this->addFieldToFilter('profile_id', ['eq' => $profileId]);
        } else {
            $this->_totalRecords = 0;
            $this->_setIsLoaded(true);
        }
        return $this;
    }
}
