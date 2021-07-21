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
 * @package    Sarp2GraphQl
 * @version    1.0.2
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2GraphQl\Model\Resolver\Mutation;

use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Aheadworks\Sarp2GraphQl\Model\Resolver\AbstractProfileResolver;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class ProfileChangeShippingAddress
 *
 * @package Aheadworks\Sarp2GraphQl\Model\Resolver\Mutation
 */
class ProfileChangeShippingAddress extends AbstractProfileResolver
{
    /**
     * @var AddressInterfaceFactory
     */
    private $addressFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param ProfileManagementInterface $profileManagement
     * @param ProfileRepositoryInterface $profileRepository
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ProfileManagementInterface $profileManagement,
        ProfileRepositoryInterface $profileRepository,
        DataObjectHelper $dataObjectHelper
    ) {
        parent::__construct($profileManagement, $profileRepository);
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @inheritdoc
     */
    public function performResolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($args['profile_id']) || empty($args['profile_id'])) {
            throw new GraphQlInputException(__('Specify the "profile_id" value.'));
        }

        if (!isset($args['address']) || empty($args['address'])) {
            throw new GraphQlInputException(__('Specify the "address" value.'));
        }

        $address = $this->createAddress($args['address']);

        return $this->profileManagement->changeShippingAddress(
            $args['profile_id'],
            $address
        );
    }

    /**
     * Create address object from data array
     *
     * @param $data
     * @return AddressInterface
     */
    private function createAddress($data)
    {
        $address = $this->addressFactory->create();
        $this->dataObjectHelper->populateWithArray($address, $data, AddressInterface::class);

        return $address;
    }
}
