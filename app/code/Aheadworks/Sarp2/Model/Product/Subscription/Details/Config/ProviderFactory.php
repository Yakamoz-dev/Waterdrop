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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Details\Config;

use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\AbstractProvider;
use Magento\Framework\ObjectManagerInterface;

class ProviderFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create subscription details config provider instance
     *
     * @param string $className
     * @return AbstractProvider
     */
    public function create($className)
    {
        $instance = $this->objectManager->create($className);
        if (!$instance instanceof AbstractProvider) {
            throw new \InvalidArgumentException(
                $className . ' doesn\'t extend ' . AbstractProvider::class
            );
        }

        return $instance;
    }
}
