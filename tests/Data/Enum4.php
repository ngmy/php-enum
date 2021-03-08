<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests\Data;

use Ngmy\Enum\Enum;

/**
 * @method static self FOO()
 * @method static self BAR()
 * @method static self BAZ()
 */
class Enum4 extends Enum
{
    /**
     * @var null
     * @enum
     */
    protected static $FOO;
    /**
     * @var null
     * @enum
     */
    protected static $BAR;
    /**
     * @var null
     * @enum
     */
    protected static $BAZ;
}
