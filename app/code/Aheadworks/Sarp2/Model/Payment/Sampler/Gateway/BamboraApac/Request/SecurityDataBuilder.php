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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\BamboraApac\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;
use Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\SubjectReader;
use Magento\Payment\Gateway\ConfigInterface;

/**
 * Class SecurityDataBuilder
 *
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\BamboraApac\Request
 */
class SecurityDataBuilder implements BuilderInterface
{
    /**#@+
     * Security block names
     */
    const SECURITY = 'Security';
    const USER_NAME = 'UserName';
    const PASSWORD = 'Password';
    /**#@-*/

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param SubjectReader $subjectReader
     * @param ConfigInterface $config
     */
    public function __construct(
        SubjectReader $subjectReader,
        ConfigInterface $config
    ) {
        $this->subjectReader = $subjectReader;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);

        $payment = $paymentDO->getPayment();
        $storeId = $payment->getStoreId();

        $isSandbox = $this->config->isSandboxMode($storeId);
        $result = [
            self::SECURITY => [
                self::USER_NAME => $isSandbox ? $this->config->getSandboxUserName() : $this->config->getUserName(),
                self::PASSWORD => $isSandbox ? $this->config->getSandboxPassword() : $this->config->getPassword()
            ]
        ];

        return $result;
    }
}
