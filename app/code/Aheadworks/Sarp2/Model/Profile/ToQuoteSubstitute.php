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
namespace Aheadworks\Sarp2\Model\Profile;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Model\Quote\Substitute\Quote as QuoteSubstitute;
use Aheadworks\Sarp2\Model\Quote\Substitute\QuoteFactory as QuoteSubstituteFactory;
use Magento\Framework\DataObject\Copy;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ToQuoteSubstitute
 * @package Aheadworks\Sarp2\Model\Profile
 */
class ToQuoteSubstitute
{
    /**
     * @var QuoteSubstituteFactory
     */
    private $substituteFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Copy
     */
    private $objectCopyService;

    /**
     * @param QuoteSubstituteFactory $substituteFactory
     * @param StoreManagerInterface $storeManager
     * @param Copy $objectCopyService
     */
    public function __construct(
        QuoteSubstituteFactory $substituteFactory,
        StoreManagerInterface $storeManager,
        Copy $objectCopyService
    ) {
        $this->substituteFactory = $substituteFactory;
        $this->storeManager = $storeManager;
        $this->objectCopyService = $objectCopyService;
    }

    /**
     * Convert profile into quote substitute
     *
     * @param ProfileInterface $profile
     * @param string $totalsGroupCode
     * @param array $data
     * @return QuoteSubstitute
     */
    public function convert($profile, $totalsGroupCode, $data = [])
    {
        /** @var QuoteSubstitute $substitute */
        $substitute = $this->substituteFactory->create();
        $this->objectCopyService->copyFieldsetToTarget(
            'aw_sarp2_convert_profile',
            'to_quote_substitute',
            $profile,
            $substitute
        );

        $substitute->addData(
            array_merge(
                [
                    'store' => $this->storeManager->getStore($profile->getStoreId()),
                    'coupon_code' => null
                ],
                $data
            )
        );

        return $substitute;
    }
}
