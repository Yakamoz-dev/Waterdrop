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
namespace Aheadworks\Sarp2GraphQl\Model\Resolver;

use Aheadworks\Sarp2\Api\Data\ScheduledPaymentInfoInterface;
use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class ProfileGetNextPaymentInfo
 *
 * @package Aheadworks\Sarp2GraphQl\Model\Resolver
 */
class ProfileGetNextPaymentInfo extends AbstractProfileResolver
{
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @param ProfileManagementInterface $profileManagement
     * @param ProfileRepositoryInterface $profileRepository
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        ProfileManagementInterface $profileManagement,
        ProfileRepositoryInterface $profileRepository,
        DataObjectProcessor $dataObjectProcessor
    ) {
        parent::__construct($profileManagement, $profileRepository);
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function performResolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {

        $profileId = $args['profile_id'] ?? $value['profile_id'];
        $info = $this->profileManagement->getNextPaymentInfo($profileId);

        return $this->dataObjectProcessor->buildOutputDataArray(
            $info,
            ScheduledPaymentInfoInterface::class
        );
    }
}
