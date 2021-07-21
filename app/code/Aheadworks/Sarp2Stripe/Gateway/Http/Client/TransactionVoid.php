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
 * @package    Sarp2Stripe
 * @version    1.0.5
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Gateway\Http\Client;

use Aheadworks\Sarp2Stripe\Gateway\Request\TransactionVoidDataBuilder;

/**
 * Class TransactionVoid
 * @package Aheadworks\BamboraApac\Model\Api
 */
class TransactionVoid extends AbstractTransaction
{
    /**
     * {@inheritdoc}
     */
    protected function process(array $data)
    {
        return  $this->adapter->void(
            $data[TransactionVoidDataBuilder::TRANSACTION_ID]
        );
    }
}
