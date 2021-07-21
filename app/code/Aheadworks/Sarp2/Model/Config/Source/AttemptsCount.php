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
namespace Aheadworks\Sarp2\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class AttemptsCount
 *
 * @package Aheadworks\Sarp2\Model\Config\Source
 */
class AttemptsCount implements OptionSourceInterface
{
    /**
     * @var int
     */
    private $maxCount;

    /**
     * @param int $maxCount
     */
    public function __construct(
        $maxCount = 10
    ) {
        $this->maxCount = $maxCount;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $result = [];

        for ($i = 1; $i <= $this->maxCount; $i++) {
            $result[] = [
                'value' => $i,
                'label' => $i,
            ];
        }

        return $result;
    }
}
