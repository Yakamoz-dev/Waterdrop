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

use GraphQL\Error\ClientAware;
use Magento\Framework\Exception\AggregateExceptionInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class AbstractResolver
 *
 * @package Aheadworks\Sarp2GraphQl\Model\Resolver\Mutation
 */
abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->isCustomerAuthenticate($context)) {
            throw new GraphQlAuthorizationException(__('The request is allowed for logged in customer'));
        }

        $this->beforePerform($field, $context, $info, $value, $args);

        return $this->performResolve($field, $context, $info, $value, $args);
    }

    /**
     * Before perform resolve
     *
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return bool
     */
    protected function beforePerform(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        return !empty($value) && !empty($args);
    }

    /**
     * Validate customer authorization
     *
     * @param ContextInterface $context
     * @return bool
     */
    private function isCustomerAuthenticate($context)
    {
        return false !== $context->getExtensionAttributes()->getIsCustomer();
    }

    /**
     * Retrieve customer id from request context
     *
     * @param ContextInterface $context
     * @return int
     */
    protected function getCustomerId($context)
    {
        return $context->getUserId();
    }

    /**
     * Perform resolve method after validate customer authorization
     *
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return mixed
     * @throws AggregateExceptionInterface
     * @throws ClientAware
     */
    abstract protected function performResolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    );
}
