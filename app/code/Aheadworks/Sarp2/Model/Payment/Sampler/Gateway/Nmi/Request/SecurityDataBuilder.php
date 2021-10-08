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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\Nmi\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\SubjectReader;

/**
 * Class SecurityDataBuilder
 *
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\Nmi\Request
 */
class SecurityDataBuilder implements BuilderInterface
{
    /**#@+
     * Security block names
     */
    const USER_NAME = 'username';
    const PASSWORD = 'password';
    /**#@-*/

    /**
     * @var \Aheadworks\Nmi\Gateway\Config\Config
     */
    private $config;

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * SecurityDataBuilder constructor.
     *
     * @param SubjectReader $subjectReader
     * @param ConfigInterface $config
     */
    public function __construct(SubjectReader $subjectReader, ConfigInterface $config)
    {
        $this->subjectReader = $subjectReader;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);

        $profile = $paymentDO->getProfile();

        $isSandbox = $this->config->isSandboxMode($profile->getStoreId());
        $result = [
            self::USER_NAME => $isSandbox ? $this->config->getSandboxUserName() : $this->config->getUserName(),
            self::PASSWORD => $isSandbox ? $this->config->getSandboxPassword() : $this->config->getPassword()
        ];

        return $result;
    }
}
