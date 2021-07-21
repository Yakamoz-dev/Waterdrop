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
namespace Aheadworks\Sarp2\Gateway\AuthorizenetAcceptjs;

use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

/**
 * Class ProfileAdapter
 * @package Aheadworks\Sarp2\Gateway\AuthorizenetAcceptjs
 */
class ProfileAdapter
{
    /**
     * @var CommandPoolInterface
     */
    private $commandPool;

    /**
     * @param CommandPoolInterface $commandPool
     */
    public function __construct(
        CommandPoolInterface $commandPool
    ) {
        $this->commandPool = $commandPool;
    }

    /**
     * Execute profile command
     *
     * @param $commandCode
     * @param array $arguments
     * @return ResultInterface|null
     * @throws NotFoundException
     * @throws CommandException
     */
    public function execute($commandCode, array $arguments = [])
    {
        return $this->commandPool->get($commandCode)->execute($arguments);
    }
}
