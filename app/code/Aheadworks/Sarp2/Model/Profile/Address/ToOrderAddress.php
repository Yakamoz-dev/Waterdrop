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
namespace Aheadworks\Sarp2\Model\Profile\Address;

use Aheadworks\Sarp2\Api\Data\ProfileAddressInterface;
use Magento\Framework\DataObject\Copy;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderAddressInterfaceFactory;

/**
 * Class ToOrderAddress
 * @package Aheadworks\Sarp2\Model\Profile\Address
 */
class ToOrderAddress
{
    /**
     * @var OrderAddressInterfaceFactory
     */
    private $addressFactory;

    /**
     * @var Copy
     */
    private $objectCopyService;

    /**
     * @param OrderAddressInterfaceFactory $addressFactory
     * @param Copy $objectCopyService
     */
    public function __construct(
        OrderAddressInterfaceFactory $addressFactory,
        Copy $objectCopyService
    ) {
        $this->addressFactory = $addressFactory;
        $this->objectCopyService = $objectCopyService;
    }

    /**
     * Convert profile address to order address
     *
     * @param ProfileAddressInterface $profileAddress
     * @return OrderAddressInterface
     */
    public function convert(ProfileAddressInterface $profileAddress)
    {
        $address = $this->addressFactory->create();
        $this->objectCopyService->copyFieldsetToTarget(
            'aw_sarp2_convert_profile_address',
            'to_order_address',
            $profileAddress,
            $address
        );
        return $address;
    }
}
