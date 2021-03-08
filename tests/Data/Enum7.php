<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests\Data;

use InvalidArgumentException;
use Ngmy\Enum\Enum;

/**
 * @method static self FOO()
 * @method static self BAR()
 * @method static self BAZ()
 */
class Enum7 extends Enum
{
    /**
     * @var int
     * @enum
     */
    private static $FOO = 1;
    /**
     * @var int
     * @enum
     */
    private static $BAR = 2;
    /**
     * @var int
     * @enum
     */
    private static $BAZ = 3;

    public static function getInstance(int $value): self
    {
        foreach (self::values() as $enum) {
            if ($enum->getValue() == $value) {
                return $enum;
            }
        }
        throw new InvalidArgumentException('The value "%s" is invalid.');
    }

    public function getValue(): int
    {
        return self::${$this->name()};
    }
}
