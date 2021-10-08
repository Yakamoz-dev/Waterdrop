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
namespace Aheadworks\Sarp2\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface QuoteAddressItemInterface
 * @package Aheadworks\Sarp2\Api\Data
 */
interface QuoteAddressItemInterface extends ExtensibleDataInterface
{

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Sarp2\Api\Data\QuoteAddressItemExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Sarp2\Api\Data\QuoteAddressItemExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Sarp2\Api\Data\QuoteAddressItemExtensionInterface $extensionAttributes
    );
}
