<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\Enum\EnumArray;
use Ngmy\TypedArray\TypedArray;
use stdClass;

class EnumArrayTest extends TestCase
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function allOfProvider(): array
    {
        return [
            [Data\Enum1::class, TypedArray::ofClass(Data\Enum1::class, [
                Data\Enum1::valueOf('FOO'),
                Data\Enum1::valueOf('BAR'),
                Data\Enum1::valueOf('BAZ'),
            ])],
            [Data\Enum2::class, TypedArray::ofClass(Data\Enum2::class, [
                Data\Enum2::valueOf('QUX'),
                Data\Enum2::valueOf('FOO'),
                Data\Enum2::valueOf('BAR'),
                Data\Enum2::valueOf('BAZ'),
            ])],
            [stdClass::class, new InvalidArgumentException()],
        ];
    }

    /**
     * @param Exception|TypedArray<object> $expected
     * @dataProvider allOfProvider
     *
     * @phpstan-param class-string $class
     */
    public function testAllOf(string $class, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        $this->assertEquals($expected, EnumArray::allOf($class));
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function noneOfProvider(): array
    {
        return [
            [Data\Enum1::class, TypedArray::ofClass(Data\Enum1::class)],
            [Data\Enum2::class, TypedArray::ofClass(Data\Enum2::class)],
            [stdClass::class, new InvalidArgumentException()],
        ];
    }

    /**
     * @param Exception|TypedArray<object> $expected
     * @dataProvider noneOfProvider
     *
     * @phpstan-param class-string $class
     */
    public function testNoneOf(string $class, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        $this->assertEquals($expected, EnumArray::noneOf($class));
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function ofProvider(): array
    {
        return [
            [Data\Enum1::class, 'FOO', TypedArray::ofClass(Data\Enum1::class, [
                Data\Enum1::valueOf('FOO'),
            ])],
            [Data\Enum2::class, 'FOO', TypedArray::ofClass(Data\Enum2::class, [
                Data\Enum2::valueOf('FOO'),
            ])],
            [Data\Enum1::class, 'QUX', new InvalidArgumentException()],
        ];
    }

    /**
     * @param Exception|TypedArray<object> $expected
     * @dataProvider ofProvider
     *
     * @phpstan-param class-string $class
     */
    public function testOf(string $class, string $name, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        $this->assertEquals($expected, EnumArray::of($class, $name));
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function rangeProvider(): array
    {
        return [
            [Data\Enum1::class, 'FOO', 'BAZ', TypedArray::ofClass(Data\Enum1::class, [
                Data\Enum1::valueOf('FOO'),
                Data\Enum1::valueOf('BAR'),
                Data\Enum1::valueOf('BAZ'),
            ])],
            [Data\Enum1::class, 'FOO', 'BAR', TypedArray::ofClass(Data\Enum1::class, [
                Data\Enum1::valueOf('FOO'),
                Data\Enum1::valueOf('BAR'),
            ])],
            [Data\Enum1::class, 'BAR', 'BAZ', TypedArray::ofClass(Data\Enum1::class, [
                Data\Enum1::valueOf('BAR'),
                Data\Enum1::valueOf('BAZ'),
            ])],
            [Data\Enum1::class, 'FOO', 'FOO', TypedArray::ofClass(Data\Enum1::class, [
                Data\Enum1::valueOf('FOO'),
            ])],
            [Data\Enum1::class, 'BAR', 'BAR', TypedArray::ofClass(Data\Enum1::class, [
                Data\Enum1::valueOf('BAR'),
            ])],
            [Data\Enum1::class, 'BAZ', 'BAZ', TypedArray::ofClass(Data\Enum1::class, [
                Data\Enum1::valueOf('BAZ'),
            ])],
            [Data\Enum2::class, 'QUX', 'BAZ', TypedArray::ofClass(Data\Enum2::class, [
                Data\Enum2::valueOf('QUX'),
                Data\Enum2::valueOf('FOO'),
                Data\Enum2::valueOf('BAR'),
                Data\Enum2::valueOf('BAZ'),
            ])],
            [Data\Enum1::class, 'BAR', 'FOO', new InvalidArgumentException()],
            [Data\Enum1::class, 'QUX', 'BAZ', new InvalidArgumentException()],
         ];
    }

    /**
     * @param Exception|TypedArray<object> $expected
     * @dataProvider rangeProvider
     *
     * @phpstan-param class-string $class
     */
    public function testRange(string $class, string $from, string $to, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        $this->assertEquals($expected, EnumArray::range($class, $from, $to));
    }
}
