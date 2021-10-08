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
use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;
use Magento\Quote\Api\Data\AddressInterfaceFactory;
use Magento\Framework\DataObject\Copy;

/**
 * Class ToQuoteAddress
 *
 * @package Aheadworks\Sarp2\Model\Profile\Address
 */
class ToQuoteAddress
{
    /**
     * @var AddressInterfaceFactory
     */
    private $quoteAddressFactory;

    /**
     * @var Copy
     */
    private $objectCopyService;

    /**
     * @param AddressInterfaceFactory $quoteAddressFactory
     * @param Copy $objectCopyService
     */
    public function __construct(
        AddressInterfaceFactory $quoteAddressFactory,
        Copy $objectCopyService
    ) {
        $this->quoteAddressFactory = $quoteAddressFactory;
        $this->objectCopyService = $objectCopyService;
    }

    /**
     * Convert profile address to quote address
     *
     * @param ProfileAddressInterface $profileAddress
     * @param QuoteAddressInterface $quoteAddress
     * @return QuoteAddressInterface
     */
    public function convert(ProfileAddressInterface $profileAddress, QuoteAddressInterface $quoteAddress = null)
    {
        /** @var ProfileAddressInterface $profileAddress */
        $quoteAddress = $quoteAddress ? : $this->quoteAddressFactory->create();
        $this->objectCopyService->copyFieldsetToTarget(
            'aw_sarp2_convert_customer_address',
            'to_quote_address',
            $profileAddress,
            $quoteAddress
        );

        return $quoteAddress;
    }
}
