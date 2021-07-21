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
namespace Aheadworks\Sarp2\Model\Quote\Repository\InvalidData;

use Aheadworks\Sarp2\Model\Quote\Checker\HasSubscriptions;
use Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Detect\ResultInterface;
use Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Detect\ResultFactory;
use Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Reason\ValidatorList;
use Magento\Quote\Model\Quote;

/**
 * Class Detector
 * @package Aheadworks\Sarp2\Model\Quote\Repository\InvalidData
 */
class Detector
{
    /**
     * @var HasSubscriptions
     */
    private $quoteChecker;

    /**
     * @var ValidatorList
     */
    private $validatorList;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @param HasSubscriptions $quoteChecker
     * @param ValidatorList $validatorList
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        HasSubscriptions $quoteChecker,
        ValidatorList $validatorList,
        ResultFactory $resultFactory
    ) {
        $this->quoteChecker = $quoteChecker;
        $this->validatorList = $validatorList;
        $this->resultFactory = $resultFactory;
    }

    /**
     * Detect invalid cart data
     *
     * @param Quote $quote
     * @return ResultInterface
     */
    public function detect($quote)
    {
        $resultData = ['isInvalid' => false];

        if ($this->quoteChecker->check($quote)) {
            $validators = $this->validatorList->getValidators();
            $validatorsCount = count($validators);

            if ($validatorsCount) {
                $isDetected = false;
                $i = 0;
                do {
                    $validator = $validators[$i];
                    if (!$validator->validate($quote)) {
                        $resultData = array_merge(
                            $resultData,
                            [
                                'isInvalid' => true,
                                'reason' => $validator->getReason(),
                                'errorMessage' => $validator->getErrorMessage()
                            ]
                        );
                        $isDetected = true;
                    }
                    $i++;
                } while (!$isDetected && $i < $validatorsCount);
            }
        }

        return $this->resultFactory->create($resultData);
    }
}
