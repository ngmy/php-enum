<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests\Data;

use Ngmy\Enum\Enum;

/**
 * @method static static FOO()
 * @method static static BAR()
 * @method static static BAZ()
 */
class Enum6 extends Enum
{
    // @phpstan-ignore-next-line
    private static $withNoDocComment;
    /**
     * @var mixed @enum
     */
    private static $withEnumAnnotationInDescription;
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
}
