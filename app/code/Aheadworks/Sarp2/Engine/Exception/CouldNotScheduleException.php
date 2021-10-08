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
namespace Aheadworks\Sarp2\Engine\Exception;

use Aheadworks\Sarp2\Api\Exception\CouldNotScheduleExceptionInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class CouldNotScheduleException
 * @package Aheadworks\Sarp2\Engine\Exception
 */
class CouldNotScheduleException extends LocalizedException implements CouldNotScheduleExceptionInterface
{
}
