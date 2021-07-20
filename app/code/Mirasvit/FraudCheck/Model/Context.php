<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-fraud-check
 * @version   1.1.4
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\FraudCheck\Model;

use Magento\Framework\DataObject;
use Mirasvit\FraudCheck\Rule\IndicatorFactory;
use Mirasvit\FraudCheck\Service\MatchService;
use Mirasvit\FraudCheck\Service\MatchServiceFactory;

/**
 * @method string getIp()
 * @method string getFirstname()
 * @method string getLastname()
 * @method string getEmail()
 * @method int getOrderId()
 * @method string getBillingCountry()
 * @method string getBillingCity()
 * @method string getBillingState()
 * @method string getBillingStreet()
 * @method string getBillingPostcode()
 * @method string getBillingPhone()
 * @method string getBillingName()
 * @method string getShippingCountry()
 * @method string getShippingCity()
 * @method string getShippingState()
 * @method string getShippingStreet()
 * @method string getShippingPostcode()
 * @method string getShippingPhone()
 * @method string getShippingName()
 * @method float getGrandTotal()
 */
class Context extends DataObject
{
    /**
     * @var IndicatorFactory
     */
    private $indicatorFactory;

    /**
     * @var MatchServiceFactory
     */
    private $matchServiceFactory;

    /**
     * @var \Magento\Sales\Model\Order
     */
    public $order;

    /**
     * @var array
     */
    private static $messagePool = [];

    /**
     * Context constructor.
     * @param IndicatorFactory $indicatorFactory
     * @param MatchServiceFactory $matchServiceFactory
     */
    public function __construct(
        IndicatorFactory $indicatorFactory,
        MatchServiceFactory $matchServiceFactory
    ) {
        $this->indicatorFactory    = $indicatorFactory;
        $this->matchServiceFactory = $matchServiceFactory;

        parent::__construct();
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     *
     * @return $this
     */
    public function extractOrderData($order)
    {
        $this->order = $order;

        $ip = $order->getRemoteIp() && $order->getRemoteIp() != '127.0.0.1'
            ? $order->getRemoteIp()
            : $order->getXForwardedFor();

        $this->setData([
            'ip'          => $ip,
            'firstname'   => $order->getCustomerFirstname(),
            'lastname'    => $order->getCustomerLastname(),
            'email'       => $order->getCustomerEmail(),
            'order_id'    => $order->getId(),
            'grand_total' => $order->getBaseGrandTotal(),
        ]);

        if ($order->getBillingAddress()) {
            $address = $order->getBillingAddress();
            $this->addData([
                'billing_country'  => $address->getCountryId(),
                'billing_city'     => $address->getCity(),
                'billing_state'    => $address->getRegion(),
                'billing_street'   => implode(', ', $address->getStreet()),
                'billing_postcode' => $address->getPostcode(),
                'billing_phone'    => $address->getTelephone(),
                'billing_name'     => $address->getName(),
            ]);

            if (!$this->getData('firstname')) {
                $this->setData('firstname', $address->getFirstname());
            }
            if (!$this->getData('lastname')) {
                $this->setData('lastname', $address->getLastname());
            }
        }

        if ($order->getShippingAddress()) {
            $address = $order->getShippingAddress();
            $this->addData([
                'shipping_country'  => $address->getCountryId(),
                'shipping_city'     => $address->getCity(),
                'shipping_state'    => $address->getRegion(),
                'shipping_street'   => implode(', ', $address->getStreet()),
                'shipping_postcode' => $address->getPostcode(),
                'shipping_phone'    => $address->getTelephone(),
                'shipping_name'     => $address->getName(),
            ]);
        }

        return $this;
    }

    /**
     * @return IndicatorFactory
     */
    public function getIndicatorFactory()
    {
        return $this->indicatorFactory;
    }

    /**
     * @return MatchService
     */
    public function getMatchService()
    {
        return $this->matchServiceFactory->create();
    }

    /**
     * @param mixed $message
     * @return $this
     */
    public function addMessage($message)
    {
        self::$messagePool[] = $message;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getMessages()
    {
        return self::$messagePool;
    }
}
