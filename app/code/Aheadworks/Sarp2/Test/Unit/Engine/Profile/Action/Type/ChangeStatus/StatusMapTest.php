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
namespace Aheadworks\Sarp2\Test\Unit\Engine\Profile\Action\Type\ChangeStatus;

use Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangeStatus\StatusMap;
use Aheadworks\Sarp2\Model\Profile\Source\Status;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangeStatus\StatusMap
 */
class StatusMapTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var StatusMap
     */
    private $statusMap;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->statusMap = $objectManager->getObject(StatusMap::class);
    }

    /**
     * @param array $map
     * @param string $status
     * @param array $expectedResult
     * @dataProvider getAllowedStatusesDataProvider
     */
    public function testGetAllowedStatuses($map, $status, $expectedResult)
    {
        $class = new \ReflectionClass($this->statusMap);

        $mapProperty = $class->getProperty('map');
        $mapProperty->setAccessible(true);
        $mapProperty->setValue($this->statusMap, $map);

        $this->assertEquals($expectedResult, $this->statusMap->getAllowedStatuses($status));
    }

    /**
     * @return array
     */
    public function getAllowedStatusesDataProvider()
    {
        return [
            [[Status::ACTIVE => [Status::SUSPENDED]], Status::ACTIVE, [Status::SUSPENDED]],
            [[Status::ACTIVE => []], Status::ACTIVE, []],
            [[Status::ACTIVE => [Status::SUSPENDED]], Status::CANCELLED, []]
        ];
    }
}
