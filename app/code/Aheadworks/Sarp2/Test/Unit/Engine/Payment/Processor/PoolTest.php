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
 * @version    2.15.3
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Test\Unit\Engine\Payment\Processor;

use Aheadworks\Sarp2\Engine\Payment\Processor\Factory;
use Aheadworks\Sarp2\Engine\Payment\Processor\Pool;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Sarp2\Engine\Payment\Processor\Pool
 */
class PoolTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var Factory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $factoryMock;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->factoryMock = $this->createMock(Factory::class);
        $this->pool = $objectManager->getObject(
            Pool::class,
            ['factory' => $this->factoryMock]
        );
    }

    public function testGetConfiguredPaymentTypes()
    {
        $processorsDef = [
            'type1' => ['className' => 'ProcessorType1', 'sortOrder' => 2],
            'type2' => ['className' => 'ProcessorType1', 'sortOrder' => 0],
            'type3' => ['className' => 'ProcessorType1', 'sortOrder' => 1]
        ];

        $class = new \ReflectionClass($this->pool);

        $processorsProperty = $class->getProperty('processors');
        $processorsProperty->setAccessible(true);
        $processorsProperty->setValue($this->pool, $processorsDef);

        $actualTypes = $this->pool->getConfiguredPaymentTypes();

        $this->assertEquals('type2', array_shift($actualTypes));
        $this->assertEquals('type3', array_shift($actualTypes));
        $this->assertEquals('type1', array_shift($actualTypes));
    }
}
