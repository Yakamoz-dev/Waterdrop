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
namespace Aheadworks\Sarp2\Model\ResourceModel\Profile\Handler;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\ProfileAddressRepositoryInterface;
use Aheadworks\Sarp2\Model\ResourceModel\Profile\Handler\HandlerInterface;

/**
 * Class AddressHandler
 * @package Aheadworks\Sarp2\Model\ResourceModel\Profile
 */
class AddressHandler implements HandlerInterface
{
    /**
     * @var ProfileAddressRepositoryInterface
     */
    private $addressRepository;

    /**
     * @param ProfileAddressRepositoryInterface $addressRepository
     */
    public function __construct(ProfileAddressRepositoryInterface $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ProfileInterface $profile)
    {
        foreach ($profile->getAddresses() as $address) {
            $address->setProfileId($profile->getProfileId());
            $this->addressRepository->save($address);
        }
    }
}
