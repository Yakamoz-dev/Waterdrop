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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\Validator;

use Aheadworks\Sarp2\Engine\Profile\Checker\ProductsAvailable;
use Aheadworks\Sarp2\Engine\Profile\Action\Validation\AbstractValidator;

/**
 * Class IsProductsAvailable
 *
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\Validator
 */
class IsProductsAvailable extends AbstractValidator
{
    /**
     * @var ProductsAvailable
     */
    private $productsAvailableChecker;

    /**
     * @param ProductsAvailable $productsAvailableChecker
     */
    public function __construct(ProductsAvailable $productsAvailableChecker)
    {
        $this->productsAvailableChecker = $productsAvailableChecker;
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function performValidation($profile, $action)
    {
        if (!$this->productsAvailableChecker->check($profile)) {
            $this->addMessages(['The Extend action is not possible for this subscription.']);
        }
    }
}
