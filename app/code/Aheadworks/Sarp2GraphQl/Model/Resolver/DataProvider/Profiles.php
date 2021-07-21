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
 * @package    Sarp2GraphQl
 * @version    1.0.2
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2GraphQl\Model\Resolver\DataProvider;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class Profiles
 *
 * @package Aheadworks\Sarp2GraphQl\Model\Resolver\DataProvider
 */
class Profiles extends AbstractDataProvider
{
    /**
     * @var ProfileRepositoryInterface
     */
    private $profileRepository;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param ProfileRepositoryInterface $profileRepository
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        ProfileRepositoryInterface $profileRepository
    ) {
        parent::__construct($dataObjectProcessor);
        $this->profileRepository = $profileRepository;
    }

    /**
     * @inheritDoc
     */
    public function getListData(SearchCriteriaInterface $searchCriteria, $storeId = null)
    {
        $result = $this->profileRepository->getList($searchCriteria);
        $this->convertResultItemsToDataArray($result, ProfileInterface::class);

        return $result;
    }
}
