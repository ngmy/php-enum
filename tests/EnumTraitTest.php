<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

class EnumTraitTest extends TestCase
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function valueOfProvider(): array
    {
        return [
            [Data\Enum1::class, 'FOO', Data\Enum1::class],
            [Data\Enum1::class, 'BAR', Data\Enum1::class],
            [Data\Enum1::class, 'BAZ', Data\Enum1::class],
            [Data\Enum1::class, 'QUX', new InvalidArgumentException()],
            [Data\Enum2::class, 'QUX', Data\Enum2::class],
            [Data\Enum2::class, 'FOO', Data\Enum2::class],
            [Data\Enum2::class, 'BAR', Data\Enum2::class],
            [Data\Enum2::class, 'BAZ', Data\Enum2::class],
            [Data\Enum2::class, 'QUUX', new InvalidArgumentException()],
        ];
    }

    /**
     * @param Exception|string $expected
     * @dataProvider valueOfProvider
     *
     * @phpstan-param class-string           $class
     * @phpstan-param class-string|Exception $expected
     */
    public function testValueOf(string $class, string $name, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        \assert(\is_string($expected));
        $this->assertInstanceOf($expected, $class::valueOf($name));
    }

    /**
     * @param Exception|string $expected
     * @dataProvider valueOfProvider
     *
     * @phpstan-param class-string           $class
     * @phpstan-param class-string|Exception $expected
     */
    public function testMagicFactoryMethod(string $class, string $name, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        \assert(\is_string($expected));
        $this->assertInstanceOf($expected, $class::$name());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function valuesProvider(): array
    {
        return [
            [
                Data\Enum1::class,
                TypedArray::ofClass(Data\Enum1::class, [
                    Data\Enum1::valueOf('FOO'),
                    Data\Enum1::valueOf('BAR'),
                    Data\Enum1::valueOf('BAZ'),
                ]),
            ],
            [
                Data\Enum2::class,
                TypedArray::ofClass(Data\Enum2::class, [
                    Data\Enum2::valueOf('QUX'),
                    Data\Enum2::valueOf('FOO'),
                    Data\Enum2::valueOf('BAR'),
                    Data\Enum2::valueOf('BAZ'),
                ]),
            ],
        ];
    }

    /**
     * @dataProvider valuesProvider
     *
     * @phpstan-param class-string       $class
     * @phpstan-param TypedArray<object> $expected
     */
    public function testValues(string $class, TypedArray $expected): void
    {
        $this->assertEquals($expected, $class::values());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function namesProvider(): array
    {
        return [
            [
                Data\Enum1::class,
                [
                    'FOO',
                    'BAR',
                    'BAZ',
                ],
            ],
            [
                Data\Enum2::class,
                [
                    'QUX',
                    'FOO',
                    'BAR',
                    'BAZ',
                ],
            ],
        ];
    }

    /**
     * @param list<string> $expected
     * @dataProvider namesProvider
     *
     * @phpstan-param class-string $class
     */
    public function testNames(string $class, array $expected): void
    {
        $this->assertSame($expected, $class::names());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function nameProvider(): array
    {
        return [
            [Data\Enum1::valueOf('FOO'), 'FOO'],
            [Data\Enum1::valueOf('BAR'), 'BAR'],
            [Data\Enum1::valueOf('BAZ'), 'BAZ'],
            [Data\Enum2::valueOf('QUX'), 'QUX'],
            [Data\Enum2::valueOf('FOO'), 'FOO'],
            [Data\Enum2::valueOf('BAR'), 'BAR'],
            [Data\Enum2::valueOf('BAZ'), 'BAZ'],
        ];
    }

    /**
     * @param object $enum
     * @dataProvider nameProvider
     */
    public function testName($enum, string $expected): void
    {
        \assert(\method_exists($enum, 'name'));
        $this->assertSame($expected, $enum->name());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function toStringProvider(): array
    {
        return [
            [Data\Enum1::valueOf('FOO'), 'FOO'],
            [Data\Enum1::valueOf('BAR'), 'BAR'],
            [Data\Enum1::valueOf('BAZ'), 'BAZ'],
            [Data\Enum2::valueOf('QUX'), 'QUX'],
            [Data\Enum2::valueOf('FOO'), 'FOO'],
            [Data\Enum2::valueOf('BAR'), 'BAR'],
            [Data\Enum2::valueOf('BAZ'), 'BAZ'],
            [Data\Enum5::valueOf('FOO'), 'Foo'],
            [Data\Enum5::valueOf('BAR'), 'Bar'],
            [Data\Enum5::valueOf('BAZ'), 'Baz'],
        ];
    }

    /**
     * @param object $enum
     * @dataProvider toStringProvider
     */
    public function testToString($enum, string $expected): void
    {
        $this->assertSame($expected, (string) $enum);
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function ordinalProvider(): array
    {
        return [
            [Data\Enum1::valueOf('FOO'), 0],
            [Data\Enum1::valueOf('BAR'), 1],
            [Data\Enum1::valueOf('BAZ'), 2],
            [Data\Enum2::valueOf('QUX'), 0],
            [Data\Enum2::valueOf('FOO'), 1],
            [Data\Enum2::valueOf('BAR'), 2],
            [Data\Enum2::valueOf('BAZ'), 3],
        ];
    }

    /**
     * @param object $enum
     * @dataProvider ordinalProvider
     */
    public function testOridianl($enum, int $expected): void
    {
        \assert(\method_exists($enum, 'ordinal'));
        $this->assertSame($expected, $enum->ordinal());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function equalsProvider(): array
    {
        return [
            [Data\Enum1::valueOf('FOO'), Data\Enum1::valueOf('FOO'), true],
            [Data\Enum1::valueOf('BAR'), Data\Enum1::valueOf('BAR'), true],
            [Data\Enum1::valueOf('BAZ'), Data\Enum1::valueOf('BAZ'), true],
            [Data\Enum1::valueOf('FOO'), Data\Enum1::valueOf('BAR'), false],
            [Data\Enum1::valueOf('BAR'), Data\Enum1::valueOf('BAZ'), false],
            [Data\Enum1::valueOf('BAZ'), Data\Enum1::valueOf('FOO'), false],
            [Data\Enum2::valueOf('QUX'), Data\Enum2::valueOf('QUX'), true],
            [Data\Enum2::valueOf('FOO'), Data\Enum2::valueOf('FOO'), true],
            [Data\Enum2::valueOf('BAR'), Data\Enum2::valueOf('BAR'), true],
            [Data\Enum2::valueOf('BAZ'), Data\Enum2::valueOf('BAZ'), true],
            [Data\Enum2::valueOf('QUX'), Data\Enum2::valueOf('BAZ'), false],
            [Data\Enum2::valueOf('FOO'), Data\Enum2::valueOf('QUX'), false],
            [Data\Enum2::valueOf('BAR'), Data\Enum2::valueOf('FOO'), false],
            [Data\Enum2::valueOf('BAZ'), Data\Enum2::valueOf('BAR'), false],
            [Data\Enum1::valueOf('FOO'), Data\Enum2::valueOf('FOO'), false],
        ];
    }

    /**
     * @param object $one
     * @param object $other
     * @dataProvider equalsProvider
     */
    public function testEquals($one, $other, bool $expected): void
    {
        \assert(\method_exists($one, 'equals'));
        $this->assertSame($expected, $one->equals($other));
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function getValueProvider(): array
    {
        return [
            [Data\Enum3::valueOf('FOO'), 1],
            [Data\Enum3::valueOf('BAR'), 2],
            [Data\Enum3::valueOf('BAZ'), 3],
            [Data\Enum4::valueOf('QUX'), 4],
            [Data\Enum4::valueOf('FOO'), 1],
            [Data\Enum4::valueOf('BAR'), 2],
            [Data\Enum4::valueOf('BAZ'), 3],
        ];
    }

    /**
     * @param object $enum
     * @param mixed  $expected
     * @dataProvider getValueProvider
     */
    public function testGetValue($enum, $expected): void
    {
        \assert(\method_exists($enum, 'getValue'));
        $this->assertSame($expected, $enum->getValue());
    }
}
