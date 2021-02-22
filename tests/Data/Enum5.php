<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests\Data;

use Ngmy\Enum\EnumTrait;

/**
 * @method static static FOO()
 * @method static static BAR()
 * @method static static BAZ()
 */
class Enum5
{
    use EnumTrait;

    /** @var null */
    protected static $FOO;
    /** @var null */
    protected static $BAR;
    /** @var null */
    protected static $BAZ;

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return \ucwords(\strtolower($this->name));
    }
}
