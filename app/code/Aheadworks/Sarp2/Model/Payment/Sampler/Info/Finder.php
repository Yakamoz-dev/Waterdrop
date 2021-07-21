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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\Info;

use Aheadworks\Sarp2\Model\Payment\Sampler\Info;
use Aheadworks\Sarp2\Model\Payment\Sampler\Info\Persistence as SamplerInfoPersistence;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\Sarp2\Model\Payment\SamplerInfoInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Finder
 *
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Info
 */
class Finder
{
    /**
     * @var SamplerInfoPersistence
     */
    private $samplerInfoPersistence;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param SamplerInfoPersistence $samplerInfoPersistence
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        SamplerInfoPersistence $samplerInfoPersistence,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->samplerInfoPersistence = $samplerInfoPersistence;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Retrieve array with info about sample payments to revert
     *
     * @param int|null $limit
     * @return Info[]
     */
    public function getSamplePaymentsToRevert($limit = null)
    {
        $this->searchCriteriaBuilder->addFilter('status', SamplerInfoInterface::STATUS_PLACED);
        if (!empty($limit)) {
            $this->searchCriteriaBuilder->setPageSize($limit);
        }

        try {
            $samplePaymentsInfoArray = $this->samplerInfoPersistence->getList($this->searchCriteriaBuilder->create());
        } catch (NoSuchEntityException $exception) {
            $samplePaymentsInfoArray = [];
        }

        return $samplePaymentsInfoArray;
    }
}
