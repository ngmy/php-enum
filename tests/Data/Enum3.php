<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests\Data;

use Ngmy\Enum\Enum;

use function strtolower;
use function ucwords;

/**
 * @method static self FOO()
 * @method static self BAR()
 * @method static self BAZ()
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
        return ucwords(strtolower($this->name()));
    }
}
