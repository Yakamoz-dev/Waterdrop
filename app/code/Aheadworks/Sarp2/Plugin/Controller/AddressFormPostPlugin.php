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
namespace Aheadworks\Sarp2\Plugin\Controller;

use Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\Address;
use Magento\Customer\Controller\Address\FormPost;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

/**
 * Class AddressFormPostPlugin
 * @package Aheadworks\Sarp2\Plugin\Controller
 */
class AddressFormPostPlugin
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @param RequestInterface $request
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        RequestInterface $request,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->request = $request;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    /**
     * Redirect to sarp2 profile if needed
     *
     * @param FormPost $subject
     * @param \Closure $proceed
     * @return \Magento\Framework\Controller\Result\Redirect|mixed
     */
    public function aroundExecute($subject, \Closure $proceed)
    {
        $resultRedirect = $proceed();
        if ($profileId = $this->request->getParam(Address::REDIRECT_TO_SARP2_PROFILE)) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath(
                'aw_sarp2/profile_edit/address',
                ['profile_id' => $profileId]
            );
        }
        return $resultRedirect;
    }
}
