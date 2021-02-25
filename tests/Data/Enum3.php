<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests\Data;

use Ngmy\Enum\Enum;

/**
 * @method static static FOO()
 * @method static static BAR()
 * @method static static BAZ()
 */
class Enum3 extends Enum
{
    /**
     * @var null
     * @enum
     */
    private static $FOO;
    /**
     * @var null
     * @enum
     */
    private static $BAR;
    /**
     * @var null
     * @enum
     */
    private static $BAZ;

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return \ucwords(\strtolower($this->name()));
    }
}
