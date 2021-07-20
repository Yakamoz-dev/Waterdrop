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



namespace Mirasvit\FraudCheck\Block\Adminhtml\Order\View;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Registry;
use Mirasvit\FraudCheck\Api\Service\MatchServiceInterface;
use Mirasvit\FraudCheck\Api\Service\RenderServiceInterface;
use Mirasvit\FraudCheck\Model\Config;
use Mirasvit\FraudCheck\Model\Context;
use Mirasvit\FraudCheck\Model\Score;
use Mirasvit\FraudCheck\Model\ScoreFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Tab extends Template implements TabInterface
{
    /**
     * @var string
     */
    protected $_template = 'order/view/tab.phtml';

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Score
     */
    private $score;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var MatchServiceInterface
     */
    private $matchService;

    /**
     * @var RenderServiceInterface
     */
    private $renderService;

    /**
     * @var Config
     */
    private $config;

    /**
     * Tab constructor.
     * @param ScoreFactory $scoreFactory
     * @param Context $fraudContext
     * @param MatchServiceInterface $matchService
     * @param RenderServiceInterface $renderService
     * @param Config $config
     * @param Template\Context $context
     * @param Registry $registry
     */
    public function __construct(
        ScoreFactory $scoreFactory,
        Context $fraudContext,
        MatchServiceInterface $matchService,
        RenderServiceInterface $renderService,
        Config $config,
        Template\Context $context,
        Registry $registry
    ) {
        $this->context       = $fraudContext;
        $this->matchService  = $matchService;
        $this->renderService = $renderService;
        $this->config        = $config;
        $this->registry      = $registry;

        $this->score = $scoreFactory->create()
            ->setOrder($this->getOrder());

        parent::__construct($context);
    }

    /**
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->registry->registry('current_order');
    }

    /**
     * @return \Mirasvit\FraudCheck\Model\Score
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return array
     */
    public function getCustomerLocation()
    {
        $location = $this->matchService->getIpLocation($this->context->getIp());

        return [
            'lat' => $location ? floatval($location->getLat()) : 0,
            'lng' => $location ? floatval($location->getLng()) : 0,
        ];
    }

    /**
     * @return array
     */
    public function getShippingLocation()
    {
        $location = $this->matchService->getCoordinates(
            $this->context->getShippingCountry(),
            $this->context->getShippingCity(),
            $this->context->getShippingStreet(),
            $this->context->getShippingState()
        );

        return [
            'lat' => $location ? floatval($location->getLat()) : 0,
            'lng' => $location ? floatval($location->getLng()) : 0,
        ];
    }

    /**
     * @return array
     */
    public function getBillingLocation()
    {
        $location = $this->matchService->getCoordinates(
            $this->context->getBillingCountry(),
            $this->context->getBillingCity(),
            $this->context->getBillingStreet(),
            $this->context->getBillingState()
        );

        return [
            'lat' => $location ? floatval($location->getLat()) : 0,
            'lng' => $location ? floatval($location->getLng()) : 0,
        ];
    }

    /**
     * @return string|bool
     */
    public function getFacebookUrl()
    {
        return $this->matchService->getFacebookUrl(
            $this->context->getFirstname(),
            $this->context->getLastname()
        );
    }

    /**
     * @return string|bool
     */
    public function getTwitterUrl()
    {
        return $this->matchService->getTwitterUrl(
            $this->context->getFirstname(),
            $this->context->getLastname()
        );
    }

    /**
     * @return string|bool
     */
    public function getLinkedInUrl()
    {
        return $this->matchService->getLinkedInUrl(
            $this->context->getFirstname(),
            $this->context->getLastname()
        );
    }

    /**
     * @return string
     */
    public function getGoogleApiKey()
    {
        return $this->config->getGoogleApiKey();
    }

    /**
     * @return string[]
     */
    public function getMessages()
    {
        return $this->context->getMessages();
    }

    /**
     * Get Tab Url
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('fraud_check/order/tab', ['_current' => true]);
    }

    /**
     * Get Tab Class
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax only';
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Fraud Risk Score');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabelScoreBadge()
    {
        $score = $this->score->getFraudScore(true);

        return $this->renderService->getScoreBadgeHtml($this->score->getFraudStatus($score), $score);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Fraud Risk Score');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isOrderFromAdmin()
    {
        return !$this->context->getIp();
    }
}
