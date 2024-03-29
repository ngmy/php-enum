<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * @method static self FOO()
 * @method static self BAR()
 * @method static self BAZ()
 */
class Enum1 extends Ngmy\Enum\Enum
{
    /** @enum */
    private static $FOO;
    /** @enum */
    private static $BAR;
    /** @enum */
    private static $BAZ;
}

// Returns the enum constant of the specified name
$foo = Enum1::valueOf('FOO');
$bar = Enum1::valueOf('BAR');
$baz = Enum1::valueOf('BAZ');
// You can also use magic factory methods
$foo = Enum1::FOO();
$bar = Enum1::BAR();
$baz = Enum1::BAZ();

// Returns the name of this enum constant, exactly as declared in its enum declaration
echo $foo->name() . PHP_EOL; // FOO
echo $bar->name() . PHP_EOL; // BAR
echo $baz->name() . PHP_EOL; // BAZ

// Returns the name of this enum constant, as contained in the declaration
echo $foo . PHP_EOL; // FOO
echo $bar . PHP_EOL; // BAR
echo $baz . PHP_EOL; // BAZ

// Returns the ordinal of this enum constant
echo $foo->ordinal() . PHP_EOL; // 0
echo $bar->ordinal() . PHP_EOL; // 1
echo $baz->ordinal() . PHP_EOL; // 2

// Returns true if the specified object is equal to this enum constant
echo var_export($foo->equals($foo), true) . PHP_EOL;                  // true
echo var_export($foo->equals(Enum1::valueOf('FOO')), true) . PHP_EOL; // true
echo var_export($foo->equals($bar), true) . PHP_EOL;                  // false

// You can also have the enum constant with a value

/**
 * @method static self FOO()
 * @method static self BAR()
 * @method static self BAZ()
 */
class Enum2 extends Ngmy\Enum\Enum
{
    /** @enum */
    private static $FOO = 1;
    /** @enum */
    private static $BAR = 2;
    /** @enum */
    private static $BAZ = 3;

    public function getValue(): int
    {
        return self::${$this->name()};
    }
}

echo Enum2::valueOf('FOO')->getValue() . PHP_EOL; // 1
echo Enum2::valueOf('BAR')->getValue() . PHP_EOL; // 2
echo Enum2::valueOf('BAZ')->getValue() . PHP_EOL; // 3
