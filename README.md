# PHP Enum
[![Latest Stable Version](https://poser.pugx.org/ngmy/enum/v)](//packagist.org/packages/ngmy/enum)
[![Total Downloads](https://poser.pugx.org/ngmy/enum/downloads)](//packagist.org/packages/ngmy/enum)
[![Latest Unstable Version](https://poser.pugx.org/ngmy/enum/v/unstable)](//packagist.org/packages/ngmy/enum)
[![License](https://poser.pugx.org/ngmy/enum/license)](//packagist.org/packages/ngmy/enum)
[![composer.lock](https://poser.pugx.org/ngmy/enum/composerlock)](//packagist.org/packages/ngmy/enum)
[![PHP CI](https://github.com/ngmy/php-typed-array/actions/workflows/php.yml/badge.svg)](https://github.com/ngmy/php-typed-array/actions/workflows/php.yml)
[![Coverage Status](https://coveralls.io/repos/github/ngmy/php-enum/badge.svg?branch=master)](https://coveralls.io/github/ngmy/php-enum?branch=master)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

PHP Enum is the enumeration type for PHP.

- Interface like the enum type of Java
- Also provides the enum map and set like Java
- Supports the static analysis like PHPStan. Please see [examples](/examples)

```php
/**
 * @method static static FOO()
 * @method static static BAR()
 * @method static static BAZ()
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
 * @method static static FOO()
 * @method static static BAR()
 * @method static static BAZ()
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
```

## Requirements
PHP Enum has the following requirements:

* PHP >= 7.3

## Installation
Execute the Composer `require` command:
```console
composer require ngmy/enum
```

## Documentation
Please see the [API documentation](https://ngmy.github.io/php-enum/api/).

## License
PHP Enum is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
