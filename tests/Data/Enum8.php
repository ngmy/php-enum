<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests\Data;

use Ngmy\Enum\Enum;

/**
 * @method static self HOGE()
 * @method static self FUGA()
 */
class Enum8 extends Enum
{
    /**
     * @var null
     * @enum
     */
    private static $HOGE;
    /**
     * @var null
     * @enum
     */
    private static $FUGA;
}
