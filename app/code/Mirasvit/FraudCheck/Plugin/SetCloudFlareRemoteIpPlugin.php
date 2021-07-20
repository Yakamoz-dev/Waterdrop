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



namespace Mirasvit\FraudCheck\Plugin;

use Magento\Framework\App\RequestInterface;
use \Magento\Sales\Api\Data\OrderInterface;

/**
 * @see \Magento\Sales\Api\Data\OrderInterface::setRemoteIp()
 */
class SetCloudFlareRemoteIpPlugin
{
    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param OrderInterface $subject
     * @param string         $remoteIp
     *
     * @return array
     */
    public function beforeSetRemoteIp($subject, $remoteIp)
    {
        $ip = $this->request->getServer('HTTP_CF_CONNECTING_IP');
        if ($ip && filter_var($ip, FILTER_VALIDATE_IP)) {
            return [$ip];
        }

        return [$remoteIp];
    }
}
