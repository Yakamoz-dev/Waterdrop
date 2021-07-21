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
namespace Aheadworks\Sarp2\Engine\Payment\Action;

use Aheadworks\Sarp2\Engine\Payment\ActionInterface;
use Aheadworks\Sarp2\Engine\Payment\Action\Type\Bundled;
use Aheadworks\Sarp2\Engine\Payment\Action\Type\Single;

/**
 * Class Pool
 * @package Aheadworks\Sarp2\Engine\Payment\Action
 */
class Pool
{
    /**
     * @var ActionInterface
     */
    private $instances = [];

    /**
     * @var array
     */
    private $actions = [
        ActionInterface::TYPE_SINGLE => Single::class,
        ActionInterface::TYPE_BUNDLED => Bundled::class
    ];

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param Factory $factory
     * @param array $actions
     */
    public function __construct(
        Factory $factory,
        array $actions = []
    ) {
        $this->factory = $factory;
        $this->actions = array_merge($this->actions, $actions);
    }

    /**
     * Get payment action instance
     *
     * @param string $actionType
     * @return ActionInterface
     * @throws \Exception
     */
    public function getAction($actionType)
    {
        if (!isset($this->instances[$actionType])) {
            if (!isset($this->actions[$actionType])) {
                throw new \InvalidArgumentException(
                    sprintf('Unknown payment action: %s requested', $actionType)
                );
            }
            $this->instances[$actionType] = $this->factory->create($this->actions[$actionType]);
        }
        return $this->instances[$actionType];
    }
}
