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
namespace Aheadworks\Sarp2\Model\Profile;

/**
 * Class SequenceConfig
 * @package Aheadworks\Sarp2\Model\Profile
 */
class SequenceConfig
{
    /**
     * @var array
     */
    private $defaultValues = [
        'prefix' => '',
        'suffix' => '',
        'startValue' => 1,
        'step' => 1,
        'warningValue' => 4294966295,
        'maxValue' => 4294967295
    ];

    /**
     * Get configuration field
     *
     * @param string|null $key
     * @return mixed
     */
    public function get($key = null)
    {
        if (!array_key_exists($key, $this->defaultValues)) {
            return null;
        }
        return $this->defaultValues[$key];
    }
}
