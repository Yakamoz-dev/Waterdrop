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
namespace Aheadworks\Sarp2\Engine\Profile\Merger\Field;

/**
 * Class Specification
 * @package Aheadworks\Sarp2\Engine\Profile\Merger\Field
 */
class Specification
{
    /**
     * Field types
     */
    const TYPE_SAME = 1;
    const TYPE_SAME_IF_POSSIBLE = 2;
    const TYPE_RESOLVABLE = 3;

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Get field merging type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
