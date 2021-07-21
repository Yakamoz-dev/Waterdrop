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
namespace Aheadworks\Sarp2\Model\Plan\PostDataProcessor;

use Magento\Framework\Stdlib\BooleanUtils;

/**
 * Class Titles
 * @package Aheadworks\Sarp2\Model\Plan\PostDataProcessor
 */
class Titles implements ProcessorInterface
{
    /**
     * @var BooleanUtils
     */
    private $booleanUtils;

    /**
     * @param BooleanUtils $booleanUtils
     */
    public function __construct(BooleanUtils $booleanUtils)
    {
        $this->booleanUtils = $booleanUtils;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareEntityData($data)
    {
        if (isset($data['titles'])) {
            foreach ($data['titles'] as $index => $titleData) {
                $isRemoved = isset($titleData['removed'])
                    ? $this->booleanUtils->toBoolean($titleData['removed'])
                    : true;

                if ($isRemoved) {
                    unset($data['titles'][$index]);
                }
            }
        }

        return $data;
    }
}
