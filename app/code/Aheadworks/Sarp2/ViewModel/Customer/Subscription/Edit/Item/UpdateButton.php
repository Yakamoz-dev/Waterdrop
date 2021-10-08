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
namespace Aheadworks\Sarp2\ViewModel\Customer\Subscription\Edit\Item;

use Aheadworks\Sarp2\Model\Config;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class UpdateButton
 *
 * @package Aheadworks\Sarp2\ViewModel\Customer\Subscription\Edit\Item
 */
class UpdateButton implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Check if allow one-time editing product item
     *
     * @return int
     */
    public function canOneTimeEditing()
    {
        return (int)$this->config->canOneTimeEditProductItem();
    }
}
