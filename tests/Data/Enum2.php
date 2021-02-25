<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests\Data;

use Ngmy\Enum\Enum;

/**
 * @method static static FOO()
 * @method static static BAR()
 * @method static static BAZ()
 */
class Enum2 extends Enum
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

    public function getValue(): int
    {
        return self::${$this->name()};
    }
}
