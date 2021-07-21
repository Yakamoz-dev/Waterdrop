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
namespace Aheadworks\Sarp2\Test\Integration\Engine\Payment\Processor\Outstanding;

use Aheadworks\Sarp2\Engine\Payment\Processor\Outstanding\Detector;
use Aheadworks\Sarp2\Engine\Payment\Processor\Outstanding\DetectResult;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class DetectorStub
 * @package Aheadworks\Sarp2\Test\Integration\Engine\Payment\Processor\Outstanding
 */
class DetectorStub extends Detector
{
    /**
     * {@inheritdoc}
     */
    public function detect($payments)
    {
        return Bootstrap::getObjectManager()->create(
            DetectResult::class,
            [
                'todayPayments' => $payments,
                'outstandingPayments' => []
            ]
        );
    }
}
