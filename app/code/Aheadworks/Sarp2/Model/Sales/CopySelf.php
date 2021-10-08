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
namespace Aheadworks\Sarp2\Model\Sales;

use Magento\Framework\DataObject;

/**
 * Class CopySelf
 * @package Aheadworks\Sarp2\Model\Sales
 */
class CopySelf
{
    /**
     * Perform copy of self fields by map
     *
     * @param DataObject|array $object
     * @param array $map
     * @return DataObject|array
     */
    public function copyByMap($object, array $map)
    {
        $objectData = $object instanceof DataObject
            ? $object->getData()
            : $object;

        foreach ($map as $mapItem) {
            list($from, $to) = $mapItem;

            if (!$from || !$to) {
                throw new \InvalidArgumentException('Incorrect map item.');
            }
            if (isset($objectData[$from])) {
                $this->setFieldValue($object, $to, $objectData[$from]);
            }
        }

        return $object;
    }

    /**
     * @param DataObject|array $object
     * @param string $field
     * @param mixed $value
     * @return void
     */
    private function setFieldValue(&$object, $field, $value)
    {
        if ($object instanceof DataObject) {
            $object->setDataUsingMethod($field, $value);
        } else {
            $object[$field] = $value;
        }
    }
}
