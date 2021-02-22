<?php

declare(strict_types=1);

namespace Ngmy\Enum;

use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

use function Ngmy\TypedArray\class_uses_recursive;

class EnumArray
{
    /**
     * Creates an enum set containing all of the elements in the specified element type.
     *
     * @return TypedArray<mixed>
     *
     * @phpstan-template T
     * @phpstan-param class-string<T> $class
     * @phpstan-return TypedArray<T>
     */
    public static function allOf(string $class): TypedArray
    {
        self::validateClass($class);
        return $class::values();
    }

    /**
     * Creates an empty enum set with the specified element type.
     *
     * @return TypedArray<mixed>
     *
     * @phpstan-template T
     * @phpstan-param class-string<T> $class
     * @phpstan-return TypedArray<T>
     */
    public static function noneOf(string $class): TypedArray
    {
        self::validateClass($class);
        return TypedArray::ofClass($class);
    }

    /**
     * Creates an enum set initially containing the specified element.
     *
     * @return TypedArray<mixed>
     *
     * @phpstan-template T
     * @phpstan-param class-string<T> $class
     * @phpstan-return TypedArray<T>
     */
    public static function of($class, string $name): TypedArray
    {
        self::validateClass($class);
        return TypedArray::ofClass($class, [$class::$name()]);
    }

    /**
     * Creates an enum set initially containing all of the elements in the range defined by the two specified endpoints.
     *
     * @return TypedArray<mixed>
     *
     * @phpstan-template T
     * @phpstan-param class-string<T> $class
     * @phpstan-return TypedArray<T>
     */
    public static function range(string $class, string $from, string $to): TypedArray
    {
        self::validateClass($class);
        $enumFrom = $class::$from();
        $enumTo = $class::$to();
        $ordinalFrom = $enumFrom->ordinal();
        $ordinalTo = $enumTo->ordinal();
        if ($ordinalFrom > $ordinalTo) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The range "%s" (position %s) to "%s" (position %s) is invalid.',
                    $from,
                    $ordinalFrom,
                    $to,
                    $ordinalTo
                )
            );
        }
        $items = [];
        $names = $class::names();
        $nameCount = \count($names);
        for ($i = 0; $i < $nameCount; ++$i) {
            if ($i < $ordinalFrom) {
                continue;
            }
            if ($i > $ordinalTo) {
                break;
            }
            $items[] = $class::{$names[$i]}();
        }
        return TypedArray::ofClass($class, $items);
    }

    /**
     * @phpstan-param class-string $class
     */
    private static function validateClass(string $class): void
    {
        $usingTraits = class_uses_recursive($class);
        if (!isset($usingTraits[EnumTrait::class])) {
            throw new InvalidArgumentException();
        };
    }
}
