<?php

/**
 * Magedelight
 * Copyright (C) 2019 Magedelight <info@magedelight.com>
 *
 * @category Magedelight
 * @package Magedelight_BundlediscountGraphQl
 * @copyright Copyright (c) 2019 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

declare (strict_types = 1);

namespace Magedelight\BundlediscountGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\ConfigInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\Argument\FieldEntityAttributesInterface;

/**
 * Class FilterArgument
 * @package Magedelight\BundlediscountGraphQl\Model\Resolver
 */
class FilterArgument implements FieldEntityAttributesInterface {
	/** @var ConfigInterface */
	private $config;

	/**
	 * FilterArgument constructor.
	 * @param ConfigInterface $config
	 */
	public function __construct(ConfigInterface $config) {
		$this->config = $config;
	}

	/**
	 * @return array
	 */
	public function getEntityAttributes(): array
	{
		$fields = [];
		/** @var Field $field */
		foreach ($this->config->getConfigElement('BundleOptionsFilterOutput')->getFields() as $field) {
			$fields[$field->getName()] = '';
		}
		return array_keys($fields);
	}
}