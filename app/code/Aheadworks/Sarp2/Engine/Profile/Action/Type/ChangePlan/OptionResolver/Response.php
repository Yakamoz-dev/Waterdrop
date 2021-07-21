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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangePlan\OptionResolver;

use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;

/**
 * Class Response
 *
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangePlan\OptionResolver
 */
class Response
{
    /**
     * @var int
     */
    private $awSarp2SubscriptionType;

    /**
     * @var SubscriptionOptionInterface
     */
    private $option;

    /**
     * Retrieve subscription type
     *
     * @return int
     */
    public function getSubscriptionType()
    {
        return $this->awSarp2SubscriptionType;
    }

    /**
     * Set subscription type
     *
     * @param int $awSarp2SubscriptionType
     * @return Response
     */
    public function setAwSarp2SubscriptionType($awSarp2SubscriptionType)
    {
        $this->awSarp2SubscriptionType = $awSarp2SubscriptionType;
        return $this;
    }

    /**
     * Retrieve option
     *
     * @return SubscriptionOptionInterface
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Set option
     *
     * @param SubscriptionOptionInterface $option
     * @return Response
     */
    public function setOption(SubscriptionOptionInterface $option)
    {
        $this->option = $option;
        return $this;
    }
}
