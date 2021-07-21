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
namespace Aheadworks\Sarp2\Plugin\Block;

use Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\Address;
use Magento\Customer\Block\Address\Edit;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

/**
 * Class AddressEditPlugin
 * @package Aheadworks\Sarp2\Plugin\Block
 */
class AddressEditPlugin
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param UrlInterface $urlBuilder
     * @param RequestInterface $request
     */
    public function __construct(UrlInterface $urlBuilder, RequestInterface $request)
    {
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
    }

    /**
     * Change save address url
     *
     * @param Edit $subject
     * @param string $result
     * @return string
     */
    public function afterGetSaveUrl($subject, $result)
    {
        if ($profileId = $this->request->getParam(Address::REDIRECT_TO_SARP2_PROFILE)) {
            $result = $this->urlBuilder->getUrl(
                'customer/address/formPost',
                ['_secure' => true, Address::REDIRECT_TO_SARP2_PROFILE => $profileId]
            );
        }
        return $result;
    }
}
