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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\AddItemsFromQuoteToNearest\Validator;

use Aheadworks\Sarp2\Engine\Profile\Action\Validation\AbstractValidator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Class CurrentQuote
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\AddItemsFromQuoteToNearest\Validator
 */
class CurrentQuote extends AbstractValidator
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function performValidation($profile, $action)
    {
        $data = $action->getData();
        $customerId = $data->getCustomerId();
        $storeId = $data->getStoreId();
        $currentQuote = $this->getActiveQuote($customerId, $storeId);

        if ($currentQuote) {
            $quoteItems = $currentQuote->getItems() ?: [];

            if (!$quoteItems) {
                $this->addMessages(['Current quote hasn\'t items.']);
            }
        } else {
            $this->addMessages(['Current customer hasn\'t active quotes.']);
        }
    }

    /**
     * Get active quote for current customer
     *
     * @param int $customerId
     * @param int $storeId
     * @return CartInterface|null
     */
    private function getActiveQuote($customerId, $storeId)
    {
        try {
            $quote = $this->cartRepository->getActiveForCustomer($customerId, [$storeId]);
        } catch (NoSuchEntityException $exception) {
            $quote = null;
        }

        return $quote;
    }
}
