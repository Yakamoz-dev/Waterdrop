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
namespace Aheadworks\Sarp2\Block\Email\Profile;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Block\Email\Items\AbstractItems;

/**
 * Class Items
 *
 * @method ProfileInterface getProfile()
 *
 * @package Aheadworks\Sarp2\Block\Email\Profile
 */
class Items extends AbstractItems
{
    /**
     * {@inheritdoc}
     */
    protected $_template = 'email/profile/items.phtml';

    /**
     * {@inheritdoc}
     */
    protected function getItemType($item)
    {
        /** @var ProfileItemInterface $item */
        return $item->getProductType();
    }
}
