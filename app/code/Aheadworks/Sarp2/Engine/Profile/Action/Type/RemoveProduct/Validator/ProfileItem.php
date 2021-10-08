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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\RemoveProduct\Validator;

use Aheadworks\Sarp2\Engine\Profile\Action\Validation\AbstractValidator;
use Aheadworks\Sarp2\Model\Profile\ItemManagement;

/**
 * Class ProfileItem
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\RemoveProduct\Validator
 */
class ProfileItem extends AbstractValidator
{
    /**
     * @var ItemManagement
     */
    private $itemManagement;

    /**
     * @param ItemManagement $itemManagement
     */
    public function __construct(
        ItemManagement $itemManagement
    ) {
        $this->itemManagement = $itemManagement;
    }

    /**
     * @inheritDoc
     */
    protected function performValidation($profile, $action)
    {
        if ($profile->getItemsQty() < 2) {
            $this->addMessages(['Cannot delete a single subscription item.']);
        } else {
            $itemId = $action->getData()->getItemId();
            $item = $this->itemManagement->getItemFromProfileById($itemId, $profile);
            if (!$item) {
                $this->addMessages(['This item does not belong to this subscription.']);
            }
        }
    }
}
