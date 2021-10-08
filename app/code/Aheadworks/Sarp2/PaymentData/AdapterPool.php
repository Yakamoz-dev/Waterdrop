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
namespace Aheadworks\Sarp2\PaymentData;

/**
 * Class AdapterPool
 * @package Aheadworks\Sarp2\PaymentData
 */
class AdapterPool
{
    /**
     * @var AdapterInterface[]
     */
    private $adapterInstances = [];

    /**
     * @var array
     */
    private $adapters = [];

    /**
     * @var AdapterFactory
     */
    private $adapterFactory;

    /**
     * @param AdapterFactory $adapterFactory
     * @param array $adapters
     */
    public function __construct(
        AdapterFactory $adapterFactory,
        array $adapters = []
    ) {
        $this->adapterFactory = $adapterFactory;
        $this->adapters = array_merge($this->adapters, $adapters);
    }

    /**
     * Get payment data adapter instance
     *
     * @param string $methodCode
     * @return AdapterInterface
     * @throws \Exception
     */
    public function getAdapter($methodCode)
    {
        if (!isset($this->adapterInstances[$methodCode])) {
            if (!isset($this->adapters[$methodCode])) {
                throw new \Exception(sprintf('Unknown payment data adapter: %s requested', $methodCode));
            }
            $this->adapterInstances[$methodCode] = $this->adapterFactory->create(
                $this->adapters[$methodCode]
            );
        }
        return $this->adapterInstances[$methodCode];
    }
}
