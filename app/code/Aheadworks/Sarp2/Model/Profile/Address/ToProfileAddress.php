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
 * @version    2.15.3
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Model\Profile\Address;

use Aheadworks\Sarp2\Api\Data\ProfileAddressInterface;
use Aheadworks\Sarp2\Api\Data\ProfileAddressInterfaceFactory;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\DataObject\Copy;
use Magento\Customer\Model\Address\Mapper as CustomerAddressMapper;

/**
 * Class ToProfileAddress
 * @package Aheadworks\Sarp2\Model\Profile\Address
 */
class ToProfileAddress
{
    /**
     * @var OrderAddressInterfaceFactory
     */
    private $profileAddressFactory;

    /**
     * @var Copy
     */
    private $objectCopyService;

    /**
     * @var CustomerAddressMapper
     */
    private $addressMapper;

    /**
     * @param ProfileAddressInterfaceFactory $profileAddressFactory
     * @param Copy $objectCopyService
     * @param CustomerAddressMapper $addressMapper
     */
    public function __construct(
        ProfileAddressInterfaceFactory $profileAddressFactory,
        Copy $objectCopyService,
        CustomerAddressMapper $addressMapper
    ) {
        $this->profileAddressFactory = $profileAddressFactory;
        $this->objectCopyService = $objectCopyService;
        $this->addressMapper = $addressMapper;
    }

    /**
     * Convert order address to profile address
     *
     * @param AddressInterface $customerAddress
     * @param ProfileAddressInterface $profileAddress
     * @return ProfileAddressInterface
     */
    public function convert(AddressInterface $customerAddress, $profileAddress = null)
    {
        /** @var ProfileAddressInterface $profileAddress */
        $profileAddress = $profileAddress ? : $this->profileAddressFactory->create();
        $this->objectCopyService->copyFieldsetToTarget(
            'aw_sarp2_convert_customer_address',
            'to_profile_address',
            $this->addressMapper->toFlatArray($customerAddress),
            $profileAddress
        );
        $profileAddress->setQuoteAddressId(null);

        return $profileAddress;
    }
}
