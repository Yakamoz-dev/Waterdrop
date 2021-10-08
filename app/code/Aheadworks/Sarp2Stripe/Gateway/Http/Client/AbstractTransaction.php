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
 * @version    1.0.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Gateway\Http\Client;

use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments as StripePaymentsAdapter;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\Response;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractTransaction
 * @package Aheadworks\Sarp2Stripe\Gateway\Http\Client
 */
abstract class AbstractTransaction implements ClientInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Logger
     */
    protected $customLogger;

    /**
     * @var StripePaymentsAdapter
     */
    protected $adapter;

    /**
     * @param LoggerInterface $logger
     * @param Logger $customLogger
     * @param StripePaymentsAdapter $adapter
     */
    public function __construct(
        LoggerInterface $logger,
        Logger $customLogger,
        StripePaymentsAdapter $adapter
    ) {
        $this->logger = $logger;
        $this->customLogger = $customLogger;
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $data = $transferObject->getBody();
        $log = [
            'request' => $data,
            'client' => static::class
        ];
        $response['object'] = [];

        try {
            $response['object'] = $this->process($data);
        } catch (\Exception $e) {
            $message = __($e->getMessage() ?: 'Sorry, but something went wrong');
            $this->logger->critical($message);
            throw new ClientException($message);
        } finally {
            $log['response'] = (array) $response['object'];
            $this->customLogger->debug($log);
        }

        return $response;
    }

    /**
     * Process http request
     *
     * @param array $data
     * @return Response
     */
    abstract protected function process(array $data);
}
