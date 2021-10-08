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
namespace Aheadworks\Sarp2\Controller\Profile;

use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Aheadworks\Sarp2\Controller\AbstractAction;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Registry;
use Aheadworks\Sarp2\Model\Profile\View\Action\Permission as ActionPermission;

/**
 * Class AbstractProfile
 * @package Aheadworks\Sarp2\Controller\Profile
 */
abstract class AbstractProfile extends AbstractAction
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ActionPermission
     */
    protected $actionPermission;

    /**
     * @param Context $context
     * @param ProfileRepositoryInterface $profileRepository
     * @param CustomerSession $customerSession
     * @param Registry $registry
     * @param ActionPermission $actionPermission
     */
    public function __construct(
        Context $context,
        ProfileRepositoryInterface $profileRepository,
        CustomerSession $customerSession,
        Registry $registry,
        ActionPermission $actionPermission
    ) {
        parent::__construct($context, $profileRepository);
        $this->customerSession = $customerSession;
        $this->registry = $registry;
        $this->actionPermission = $actionPermission;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->customerSession->authenticate()) {
            $this->_actionFlag->set('', 'no-dispatch', true);
            return parent::dispatch($request);
        }

        if (!$this->isProfileBelongsToCustomer()) {
            throw new NotFoundException(__('Page not found.'));
        }

        if (!$this->isActionAllowed()) {
            $this->messageManager->addErrorMessage(__(
                'This action is not available.'
            ));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/profile/index');
        }

        return parent::dispatch($request);
    }

    /**
     * Register profile
     *
     * @return $this
     * @throws NotFoundException
     */
    protected function registerProfile()
    {
        $this->registry->register('profile', $this->getProfile());
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRefererUrl()
    {
        return $this->_url->getUrl('*/*/', ['profile_id' => $this->getProfile()->getProfileId()]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getProfile()
    {
        try {
            $profileId = (int)$this->getRequest()->getParam('profile_id');
            $requestEntity = $this->profileRepository->get($profileId);
        } catch (NoSuchEntityException $e) {
            throw new NotFoundException(__('Page not found.'));
        }

        return $requestEntity;
    }

    /**
     * Check if action with profile is allowed
     *
     * @return bool
     */
    abstract protected function isActionAllowed();

    /**
     * Check if profile belongs to current customer
     *
     * @return bool
     * @throws NotFoundException
     */
    private function isProfileBelongsToCustomer()
    {
        $profile = $this->getProfile();
        if ($profile->getProfileId()
            && $profile->getCustomerId() == $this->customerSession->getCustomerId()
        ) {
            return true;
        }

        return false;
    }

    /**
     * Redirect to back page
     *
     * @return Redirect
     */
    protected function redirectToBack()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
