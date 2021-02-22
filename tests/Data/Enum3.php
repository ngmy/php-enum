<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests\Data;

use Ngmy\Enum\EnumTrait;

/**
 * @method static static FOO()
 * @method static static BAR()
 * @method static static BAZ()
 */
class Enum3
{
    use EnumTrait;

    /** @var int */
    protected static $FOO = 1;
    /** @var int */
    protected static $BAR = 2;
    /** @var int */
    protected static $BAZ = 3;

    public function getValue(): int
    {
        return static::${$this->name};
    }
}
