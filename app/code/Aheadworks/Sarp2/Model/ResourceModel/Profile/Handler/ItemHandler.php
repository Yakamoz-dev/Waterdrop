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
namespace Aheadworks\Sarp2\Model\ResourceModel\Profile\Handler;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\ProfileItemRepositoryInterface;
use Aheadworks\Sarp2\Model\ResourceModel\Profile\Handler\HandlerInterface;

/**
 * Class ItemHandler
 * @package Aheadworks\Sarp2\Model\ResourceModel\Profile
 */
class ItemHandler implements HandlerInterface
{
    /**
     * @var ProfileItemRepositoryInterface
     */
    private $itemRepository;

    /**
     * @param ProfileItemRepositoryInterface $itemRepository
     */
    public function __construct(ProfileItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ProfileInterface $profile)
    {
        foreach ($profile->getItems() as $item) {
            $item->setProfileId($profile->getProfileId());
            $this->itemRepository->save($item);
        }
    }
}
