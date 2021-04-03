<?php

declare(strict_types=1);

namespace Ngmy\Enum;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 * @implements ArrayAccess<Enum, Enum>
 * @implements IteratorAggregate<int, Enum>
 * @see https://www.php.net/manual/en/class.arrayaccess.php
 * @see https://www.php.net/manual/en/class.countable.php
 * @see https://www.php.net/manual/en/class.iteratoraggregate.php
 *
 * @phpstan-template T of Enum
 * @phpstan-implements ArrayAccess<T, T>
 * @phpstan-implements IteratorAggregate<int, T>
 *
 * @psalm-template T of Enum
 * @template-implements ArrayAccess<T, T>
 * @template-implements IteratorAggregate<int, T>
 */
class EnumSet implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var EnumMap<Enum, Enum>
     * @phpstan-var EnumMap<T, T>
     * @psalm-var EnumMap<T, T>
     */
    private $enumMap;

    /**
     * Creates an enum set containing all of the elements in the specified element type.
     *
     * @return EnumSet<Enum>
     *
     * @phpstan-template TEnum of Enum
     * @phpstan-param class-string<TEnum> $class
     * @phpstan-return EnumSet<TEnum>
     *
     * @psalm-template TEnum of Enum
     * @psalm-param class-string<TEnum> $class
     * @psalm-return EnumSet<TEnum>
     */
    public static function allOf(string $class): self
    {
        self::validateEnum($class);
        $enumSet = new self(EnumMap::new($class)->withClassValue($class));
        foreach ($class::values() as $value) {
            $enumSet[] = $value;
        }
        return $enumSet;
    }

    /**
     * Creates an empty enum set with the specified element type.
     *
     * @return EnumSet<Enum>
     *
     * @phpstan-template TEnum of Enum
     * @phpstan-param class-string<TEnum> $class
     * @phpstan-return EnumSet<TEnum>
     *
     * @psalm-template TEnum of Enum
     * @psalm-param class-string<TEnum> $class
     * @psalm-return EnumSet<TEnum>
     */
    public static function noneOf(string $class): self
    {
        self::validateEnum($class);
        return new self(EnumMap::new($class)->withClassValue($class));
    }

    /**
     * Creates an enum set initially containing the specified element.
     *
     * @return EnumSet<Enum>
     *
     * @phpstan-template TEnum of Enum
     * @phpstan-param TEnum $enum
     * @phpstan-return EnumSet<TEnum>
     *
     * @psalm-template TEnum of Enum
     * @psalm-param TEnum $enum
     * @psalm-return EnumSet<TEnum>
     */
    public static function of(Enum $enum): self
    {
        $class = \get_class($enum);
        $enumSet = new self(EnumMap::new($class)->withClassValue($class));
        $enumSet[] = $enum;
        return $enumSet;
    }

    /**
     * Creates an enum set initially containing all of the elements in the range defined by the two specified endpoints.
     *
     * @return EnumSet<Enum>
     *
     * @phpstan-template TEnum of Enum
     * @phpstan-param TEnum $from
     * @phpstan-param TEnum $to
     * @phpstan-return EnumSet<TEnum>
     *
     * @psalm-template TEnum of Enum
     * @psalm-param TEnum $from
     * @psalm-param TEnum $to
     * @psalm-return EnumSet<TEnum>
     */
    public static function range(Enum $from, Enum $to): self
    {
        $classFrom = \get_class($from);
        $classTo = \get_class($to);
        if ($classFrom != $classTo) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The range enum classes must be the same class, "%s" and "%s" given.',
                    $classFrom,
                    $classTo
                )
            );
        }
        $ordinalFrom = $from->ordinal();
        $ordinalTo = $to->ordinal();
        if ($ordinalFrom > $ordinalTo) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The range "%s" (position %s) to "%s" (position %s) is invalid.',
                    $classFrom,
                    $ordinalFrom,
                    $classTo,
                    $ordinalTo
                )
            );
        }
        $enums = [];
        $names = $classFrom::names();
        $nameCount = \count($names);
        for ($i = 0; $i < $nameCount; ++$i) {
            if ($i < $ordinalFrom) {
                continue;
            }
            if ($i > $ordinalTo) {
                break;
            }
            /**
             * @phpstan-var TEnum $enum
             * @psalm-var TEnum $enum
             */
            $enum = $classFrom::{$names[$i]}();
            $enums[] = $enum;
        }
        $enumSet = new self(EnumMap::new($classFrom)->withClassValue($classFrom));
        foreach ($enums as $enum) {
            $enumSet[] = $enum;
        }
        return $enumSet;
    }

    /**
     * Determines if the enum set is empty or not.
     */
    public function isEmpty(): bool
    {
        return $this->enumMap->isEmpty();
    }

    /**
     * Gets the enum set of values as a plain array.
     *
     * @return array<int, Enum>
     *
     * @phpstan-return list<T>
     *
     * @psalm-return list<T>
     */
    public function toArray(): array
    {
        /**
         * @var array<int, Enum>
         * @phpstan-var list<T>
         * @psalm-var list<T>
         */
        $array = $this->enumMap->toArray();
        \usort($array, function (object $enum1, object $enum2): int {
            \assert(\method_exists($enum1, 'ordinal'));
            \assert(\method_exists($enum2, 'ordinal'));
            return $enum1->ordinal() < $enum2->ordinal() ? -1 : 1;
        });
        return $array;
    }

    /**
     * @param Enum $key
     * @see https://www.php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @phpstan-param T $key
     *
     * @psalm-param T $key
     */
    public function offsetExists($key): bool
    {
        return isset($this->enumMap[$key]);
    }

    /**
     * @param Enum $key
     * @return Enum|null
     * @see https://www.php.net/manual/en/arrayaccess.offsetget.php
     *
     * @phpstan-param T $key
     * @phpstan-return T|null
     *
     * @psalm-param T $key
     * @psalm-return T|null
     */
    public function offsetGet($key)
    {
        return $this->enumMap[$key];
    }

    /**
     * @param Enum|null $key
     * @param Enum      $enum
     * @see https://www.php.net/manual/en/arrayaccess.offsetset.php
     *
     * @phpstan-param T|null $key
     * @phpstan-param T      $enum
     *
     * @psalm-param T|null $key
     * @psalm-param T      $enum
     */
    public function offsetSet($key, $enum): void
    {
        $this->enumMap[$enum] = $enum;
    }

    /**
     * @param Enum $key
     * @see https://www.php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @phpstan-param T $key
     *
     * @psalm-param T $key
     */
    public function offsetUnset($key): void
    {
        unset($this->enumMap[$key]);
    }

    /**
     * @see https://www.php.net/manual/en/countable.count.php
     *
     * @phpstan-return 0|positive-int
     *
     * @psalm-return 0|positive-int
     */
    public function count(): int
    {
        /** @psalm-var 0|positive-int */
        return \count($this->enumMap);
    }

    /**
     * @return Traversable<int, Enum>
     * @see https://www.php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @phpstan-return Traversable<int, T>
     *
     * @psalm-return Traversable<int, T>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * @phpstan-param class-string $class
     *
     * @psalm-param class-string $class
     */
    private static function validateEnum(string $class): void
    {
        if (!\class_exists($class) || \get_parent_class($class) != Enum::class) {
            throw new InvalidArgumentException(
                \sprintf('The type of the value must be the concrete enum class, "%s" given.', $class)
            );
        };
    }

    /**
     * @param EnumMap<Enum, Enum> $enumMap
     *
     * @phpstan-param EnumMap<T, T> $enumMap
     *
     * @psalm-param EnumMap<T, T> $enumMap
     */
    private function __construct(EnumMap $enumMap)
    {
        $this->enumMap = $enumMap;
    }
}
