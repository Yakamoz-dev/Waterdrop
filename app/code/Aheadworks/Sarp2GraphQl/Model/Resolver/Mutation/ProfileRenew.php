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

use Aheadworks\Sarp2GraphQl\Model\Resolver\AbstractProfileResolver;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class ProfileRenew
 *
 * @package Aheadworks\Sarp2GraphQl\Model\Resolver\Mutation
 */
class ProfileRenew extends AbstractProfileResolver
{
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
        if (!isset($args['profile_id'])) {
            throw new GraphQlInputException(__('Specify the "profile_id" value.'));
        }

        return $this->profileManagement->renew(
            $args['profile_id']
        );
    }
}
