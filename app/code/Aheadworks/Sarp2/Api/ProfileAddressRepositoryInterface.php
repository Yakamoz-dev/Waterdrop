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
namespace Aheadworks\Sarp2\Api;

/**
 * Interface ProfileAddressRepositoryInterface
 * @package Aheadworks\Sarp2\Api
 */
interface ProfileAddressRepositoryInterface
{
    /**
     * Save profile address
     *
     * @param \Aheadworks\Sarp2\Api\Data\ProfileAddressInterface $address
     * @return \Aheadworks\Sarp2\Api\Data\ProfileAddressInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException;
     * @throws \Magento\Framework\Exception\NoSuchEntityException;
     */
    public function save(\Aheadworks\Sarp2\Api\Data\ProfileAddressInterface $address);

    /**
     * Retrieve profile address
     *
     * @param int $addressId
     * @return \Aheadworks\Sarp2\Api\Data\ProfileAddressInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($addressId);
}
