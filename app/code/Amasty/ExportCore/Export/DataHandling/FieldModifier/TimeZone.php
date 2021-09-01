<?php

declare(strict_types=1);

namespace Amasty\ExportCore\Export\DataHandling\FieldModifier;

use Amasty\ExportCore\Api\Config\Profile\FieldInterface;
use Amasty\ExportCore\Api\Config\Profile\ModifierInterface;
use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;
use Amasty\ExportCore\Export\DataHandling\AbstractModifier;
use Amasty\ExportCore\Export\DataHandling\ModifierProvider;
use Amasty\ExportCore\Export\Utils\Config\ArgumentConverter;
use Amasty\ExportCore\Export\DataHandling\FieldModifier\Date\Timezones;

class TimeZone extends AbstractModifier implements FieldModifierInterface
{
    const DEFAULT_MYSQL_DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var ArgumentConverter */
    private $argumentConverter;

    public function __construct(
        $config,
        ArgumentConverter $argumentConverter
    ) {
        parent::__construct($config);
        $this->argumentConverter = $argumentConverter;
    }

    public function transform($value): string
    {
        $offset = (isset($this->config['select_input_value'])) ? (int)$this->config['select_input_value'] : 0;

        if (empty($value) || $offset === 0 || !in_array($offset, Timezones::OFFSETS)) {
            return $value;
        }

        try {
            $timestamp = strtotime($value) + $offset;
            $datetime = new \DateTime();
            $datetime->setTimestamp($timestamp);
        } catch (\Exception $e) {
            return $value;
        }

        return $datetime->format(self::DEFAULT_MYSQL_DATE_FORMAT);
    }

    public function getValue(ModifierInterface $modifier): array
    {
        $modifierData = [];
        foreach ($modifier->getArguments() as $argument) {
            $modifierData['value'][$argument->getName()] = $argument->getValue();
        }
        $modifierData['select_value'] = $modifier->getModifierClass();

        return $modifierData;
    }

    public function prepareArguments(FieldInterface $field, $requestData): array
    {
        $arguments = [];
        if (!empty($requestData['value']['select_input_value'])) {
            $arguments = $this->argumentConverter->valueToArguments(
                (int)$requestData['value']['select_input_value'],
                'select_input_value',
                'number'
            );
        }

        return $arguments;
    }

    public function getGroup(): string
    {
        return ModifierProvider::DATE_GROUP;
    }

    public function getLabel(): string
    {
        return __('Apply Timezone')->getText();
    }

    public function getJsConfig(): array
    {
        return [
            'component' => 'Amasty_ExportCore/js/fields/modifier',
            'template' => 'Amasty_ExportCore/fields/modifier',
            'childTemplate' => 'Amasty_ExportCore/fields/selectinput-modifier',
            'childComponent' => 'Amasty_ExportCore/js/fields/modifier-field'
        ];
    }
}
