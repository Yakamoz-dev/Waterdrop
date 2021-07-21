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
namespace Aheadworks\Sarp2\Block\Adminhtml\Subscription\Edit;

use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\ValidatorWrapper;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ExtendButton
 *
 * @package Aheadworks\Sarp2\Block\Adminhtml\Subscription\Edit
 */
class ExtendButton implements ButtonProviderInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var ProfileRepositoryInterface
     */
    private $profileRepository;

    /**
     * @var ValidatorWrapper
     */
    private $extendActionValidator;

    /**
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param ProfileRepositoryInterface $profileRepository
     * @param ValidatorWrapper $extendActionValidator
     */
    public function __construct(
        RequestInterface $request,
        UrlInterface $urlBuilder,
        ProfileRepositoryInterface $profileRepository,
        ValidatorWrapper $extendActionValidator
    ) {
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        $this->profileRepository = $profileRepository;
        $this->extendActionValidator = $extendActionValidator;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        $data = [];
        $profileId = $this->request->getParam('profile_id');
        if ($profileId) {
            try {
                $profile = $this->profileRepository->get($profileId);

                if ($this->extendActionValidator->isValid($profile)) {
                    $data = [
                        'label' => __('Extend Subscription'),
                        'class' => 'save',
                        'on_click' => sprintf(
                            "deleteConfirm('%s', '%s')",
                            __('Are you sure you want to do this?'),
                            $this->urlBuilder->getUrl('*/*/extend', ['profile_id' => $profileId])
                        ),
                        'sort_order' => 40
                    ];
                }
            } catch (\Exception $exception) {
            }
        }
        return $data;
    }
}
