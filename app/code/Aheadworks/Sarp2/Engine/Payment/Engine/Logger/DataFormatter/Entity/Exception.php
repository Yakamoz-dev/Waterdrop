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
namespace Aheadworks\Sarp2\Engine\Payment\Engine\Logger\DataFormatter\Entity;

use Aheadworks\Sarp2\Engine\Exception\ScheduledPaymentException;
use Aheadworks\Sarp2\Engine\Payment\Engine\Logger\DataFormatterInterface;

/**
 * Class Exception
 * @package Aheadworks\Sarp2\Engine\Payment\Engine\Logger\DataFormatter\Entity
 */
class Exception implements DataFormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format($subject)
    {
        if ($subject instanceof \Exception) {
            /** @var \Exception $subject */
            return $subject instanceof ScheduledPaymentException
                ? $subject->getMessage()
                : sprintf(
                    '"%s" has been raised with message \'%s\'',
                    get_class($subject),
                    $subject->getMessage()
                );
        }
        return '';
    }
}
