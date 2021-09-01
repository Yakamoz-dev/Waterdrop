<?php

namespace Amasty\ExportCore\Export\DataHandling\FieldModifier\Date;

use Magento\Framework\Data\OptionSourceInterface;

class Timezones implements OptionSourceInterface
{
    const OFFSETS = [
       'UTC−12:00' => -43200,
       'UTC−11:00' => -39600,
       'UTC−10:00' => -36000,
       'UTC−09:30' => -34200,
       'UTC−09:00' => -32400,
       'UTC−08:00' => -28800,
       'UTC−07:00' => -25200,
       'UTC−06:00' => -21600,
       'UTC−05:00' => -18000,
       'UTC−04:00' => -14400,
       'UTC−03:30' => -12600,
       'UTC−03:00' => -10800,
       'UTC−02:00' => -7200,
       'UTC−01:00' => -3600,
       'UTC±00:00' => 0,
       'UTC+01:00' => 3600,
       'UTC+02:00' => 7200,
       'UTC+03:00' => 10800,
       'UTC+03:30' => 12600,
       'UTC+04:00' => 14400,
       'UTC+04:30' => 16200,
       'UTC+05:00' => 18000,
       'UTC+05:30' => 19800,
       'UTC+05:45' => 20700,
       'UTC+06:00' => 21600,
       'UTC+06:30' => 23400,
       'UTC+07:00' => 25200,
       'UTC+08:00' => 28800,
       'UTC+08:45' => 31500,
       'UTC+09:00' => 32400,
       'UTC+09:30' => 34200,
       'UTC+10:00' => 36000,
       'UTC+10:30' => 37800,
       'UTC+11:00' => 39600,
       'UTC+12:00' => 43200,
       'UTC+12:45' => 45900,
       'UTC+13:00' => 46800,
       'UTC+14:00' => 50400
    ];

    public function toOptionArray(): array
    {
        $options = [];

        foreach (self::OFFSETS as $key => $value) {
            $options[] = [
                'label' => $key,
                'value' => $value
            ];
        }

        return $options;
    }
}
