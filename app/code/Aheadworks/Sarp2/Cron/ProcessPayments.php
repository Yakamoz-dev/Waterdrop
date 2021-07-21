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
namespace Aheadworks\Sarp2\Cron;

use Aheadworks\Sarp2\Engine\EngineInterface;

/**
 * Class ProcessPayments
 * @package Aheadworks\Sarp2\Cron
 */
class ProcessPayments
{
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @param EngineInterface $engine
     */
    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Perform processing of pending payments
     *
     * @return void
     */
    public function execute()
    {
        $this->engine->processPaymentsForToday();
    }
}
