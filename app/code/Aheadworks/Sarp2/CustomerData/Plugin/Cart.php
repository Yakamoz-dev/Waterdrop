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
namespace Aheadworks\Sarp2\CustomerData\Plugin;

use Aheadworks\Sarp2\CustomerData\Cart\DataProcessor;
use Magento\Checkout\CustomerData\Cart as CartData;

/**
 * Class Cart
 * @package Aheadworks\Sarp2\CustomerData\Plugin
 */
class Cart
{
    /**
     * @var DataProcessor
     */
    private $cartDataProcessor;

    /**
     * @param DataProcessor $cartDataProcessor
     */
    public function __construct(DataProcessor $cartDataProcessor)
    {
        $this->cartDataProcessor = $cartDataProcessor;
    }

    /**
     * @param CartData $subject
     * @param array $data
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetSectionData(CartData $subject, array $data)
    {
        return $this->cartDataProcessor->process($data);
    }
}
