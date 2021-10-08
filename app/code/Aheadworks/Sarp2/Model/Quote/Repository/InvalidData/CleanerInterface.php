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
namespace Aheadworks\Sarp2\Model\Quote\Repository\InvalidData;

use Magento\Quote\Model\Quote;

/**
 * Interface CleanerInterface
 * @package Aheadworks\Sarp2\Model\Quote\Repository\InvalidData
 */
interface CleanerInterface
{
    /**
     * Clean quote data
     *
     * @param Quote $quote
     * @return Quote
     */
    public function clean($quote);
}
