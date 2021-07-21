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
namespace Aheadworks\Sarp2\Gateway\AuthorizenetAcceptjs\Http;

use Magento\AuthorizenetAcceptjs\Gateway\Http\Payload\FilterInterface;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

/**
 * Class TransferFactory
 *
 * @package Aheadworks\Sarp2\Gateway\AuthorizenetAcceptjs\Http
 */
class TransferFactory implements TransferFactoryInterface
{
    /**
     * @var TransferBuilder
     */
    private $transferBuilder;

    /**
     * @var array
     */
    private $payloadFilters;

    /**
     * @param TransferBuilder $transferBuilder
     * @param FilterInterface[] $payloadFilters
     */
    public function __construct(
        TransferBuilder $transferBuilder,
        array $payloadFilters = []
    ) {
        $this->transferBuilder = $transferBuilder;
        $this->payloadFilters = $payloadFilters;
    }

    /**
     * Builds gateway transfer object
     *
     * @param array $request
     * @return TransferInterface
     */
    public function create(array $request)
    {
        foreach ($this->payloadFilters as $filter) {
            $request = $filter->filter($request);
        }

        return $this->transferBuilder
            ->setBody($request)
            ->build();
    }
}
