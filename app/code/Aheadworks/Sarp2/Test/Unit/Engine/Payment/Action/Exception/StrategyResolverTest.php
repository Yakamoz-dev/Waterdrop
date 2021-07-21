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
namespace Aheadworks\Sarp2\Test\Unit\Engine\Payment\Action\Exception;

use Aheadworks\Sarp2\Engine\Payment\Action\Exception\Strategy\DefaultStrategy;
use Aheadworks\Sarp2\Engine\Payment\Action\Exception\StrategyResolver;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Sarp2\Engine\Payment\Action\Exception\StrategyResolver
 */
class StrategyResolverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var StrategyResolver
     */
    private $resolver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->resolver = $objectManager->getObject(StrategyResolver::class);
    }

    /**
     * Test getStrategy method
     */
    public function testGetStrategy()
    {
        $paymentMethod = 'some_payment';
        $defaultStrategyMock = $this->createMock(DefaultStrategy::class);

        $this->assertEquals(
            $defaultStrategyMock,
            $this->resolver->getStrategy($paymentMethod)
        );
    }
}
