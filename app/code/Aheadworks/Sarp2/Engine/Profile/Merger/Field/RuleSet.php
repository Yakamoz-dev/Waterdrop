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

use Aheadworks\Sarp2\Engine\Profile\Merger\Field\Resolver\Factory as ResolverFactory;
use Aheadworks\Sarp2\Engine\Profile\Merger\Field\RuleSet\Definition;

/**
 * Class RuleSet
 * @package Aheadworks\Sarp2\Engine\Profile\Merger\Field
 */
class RuleSet
{
    /**
     * @var Definition
     */
    private $definition;

    /**
     * @var RuleFactory
     */
    private $ruleFactory;

    /**
     * @var SpecificationFactory
     */
    private $specificationFactory;

    /**
     * @var ResolverFactory
     */
    private $resolverFactory;

    /**
     * @param Definition $definition
     * @param RuleFactory $ruleFactory
     * @param SpecificationFactory $specificationFactory
     * @param ResolverFactory $resolverFactory
     */
    public function __construct(
        Definition $definition,
        RuleFactory $ruleFactory,
        SpecificationFactory $specificationFactory,
        ResolverFactory $resolverFactory
    ) {
        $this->definition = $definition;
        $this->ruleFactory = $ruleFactory;
        $this->specificationFactory = $specificationFactory;
        $this->resolverFactory = $resolverFactory;
    }

    /**
     * Get field data merging rules for specified entity type
     *
     * @param string $entityType
     * @return Rule[]
     */
    public function getRules($entityType)
    {
        $rules = [];

        $rulesData = $this->definition->getDefinition($entityType);
        foreach ($rulesData as $fieldName => $data) {
            $ruleInstanceData = [
                'fieldName' => $fieldName,
                'specification' => $this->specificationFactory->create(['type' => $data['spec']])
            ];
            if (isset($data['resolver'])) {
                $ruleInstanceData['resolver'] = $this->resolverFactory->create($data['resolver']);
            }
            $rules[] = $this->ruleFactory->create($ruleInstanceData);
        }

        return $rules;
    }

    /**
     * Get fields of specified type
     *
     * @param string $entityType
     * @param string $fieldType
     * @return array
     */
    public function getFields($entityType, $fieldType)
    {
        $fields = [];
        $rulesData = $this->definition->getDefinition($entityType);
        foreach ($rulesData as $fieldName => $data) {
            if ($data['spec'] == $fieldType) {
                $fields[] = $fieldName;
            }
        }
        return $fields;
    }
}
