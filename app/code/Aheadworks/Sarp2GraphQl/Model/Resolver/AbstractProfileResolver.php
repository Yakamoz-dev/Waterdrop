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

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

abstract class AbstractProfileResolver extends AbstractResolver
{
    /**
     * @var ProfileManagementInterface
     */
    protected $profileManagement;

    /**
     * @var ProfileRepositoryInterface
     */
    protected $profileRepository;

    /**
     * @param ProfileManagementInterface $profileManagement
     * @param ProfileRepositoryInterface $profileRepository
     */
    public function __construct(
        ProfileManagementInterface $profileManagement,
        ProfileRepositoryInterface $profileRepository
    ) {
        $this->profileManagement = $profileManagement;
        $this->profileRepository = $profileRepository;
    }

    /**
     * @inheritDoc
     * @throws GraphQlInputException
     */
    protected function beforePerform(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        parent::beforePerform($field, $context, $info, $value, $args);

        if ((!isset($args['profile_id']) || empty($args['profile_id']))
            && (!isset($value['profile_id']) || empty($value['profile_id']))
        ) {
            throw new GraphQlInputException(__('Specify the "profile_id" value.'));
        }

        $profileId = $args['profile_id'] ?? $value['profile_id'];
        if (!$this->isProfileBelongsToCustomer($context, $profileId)) {
            throw new GraphQlInputException(__('Invalid request'));
        }
    }

    /**
     * Check if profile belongs to current customer
     *
     * @param ContextInterface $context
     * @param int $profileId
     * @return bool
     */
    private function isProfileBelongsToCustomer($context, $profileId)
    {
        $customerId = $this->getCustomerId($context);
        $profile = $this->getProfile($profileId);
        if ($profile && $profile->getCustomerId() == $customerId
        ) {
            return true;
        }

        return false;
    }

    /**
     * Retrieve profile
     *
     * @param $profileId
     * @return ProfileInterface|null
     */
    protected function getProfile($profileId)
    {
        try {
            return $this->profileRepository->get($profileId);
        } catch (LocalizedException $e) {
            return null;
        }
    }
}
