<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests;

use Exception;
use InvalidArgumentException;
use LogicException;
use Ngmy\Enum\Enum;

class EnumTest extends TestCase
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
            [Data\Enum5::class, 'FOO', new LogicException()],
            [Data\Enum5::class, 'BAR', new LogicException()],
            [Data\Enum5::class, 'BAZ', new LogicException()],
            [Data\Enum5::class, 'QUX', new LogicException()],
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
        $actual = $class::valueOf($name);
        \assert(\is_string($expected));
        $this->assertInstanceOf($expected, $actual);
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
        $actual = $class::$name();
        \assert(\is_string($expected));
        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function valuesProvider(): array
    {
        return [
            [
                Data\Enum1::class,
                [
                    Data\Enum1::valueOf('FOO'),
                    Data\Enum1::valueOf('BAR'),
                    Data\Enum1::valueOf('BAZ'),
                ],
            ],
            [
                Data\Enum5::class,
                new LogicException(),
            ],
        ];
    }

    /**
     * @param Exception|list<Enum> $expected
     * @dataProvider valuesProvider
     *
     * @phpstan-param class-string $class
     */
    public function testValues(string $class, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
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
                Data\Enum5::class,
                new LogicException(),
            ],
        ];
    }

    /**
     * @param Exception|list<string> $expected
     * @dataProvider namesProvider
     *
     * @phpstan-param class-string $class
     */
    public function testNames(string $class, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
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
        ];
    }

    /**
     * @dataProvider nameProvider
     */
    public function testName(Enum $enum, string $expected): void
    {
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
            [Data\Enum3::valueOf('FOO'), 'Foo'],
            [Data\Enum3::valueOf('BAR'), 'Bar'],
            [Data\Enum3::valueOf('BAZ'), 'Baz'],
        ];
    }

    /**
     * @dataProvider toStringProvider
     */
    public function testToString(Enum $enum, string $expected): void
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
        ];
    }

    /**
     * @dataProvider ordinalProvider
     */
    public function testOridianl(Enum $enum, int $expected): void
    {
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
        ];
    }

    /**
     * @dataProvider equalsProvider
     */
    public function testEquals(Enum $one, Enum $other, bool $expected): void
    {
        $this->assertSame($expected, $one->equals($other));
        $this->assertSame($expected, $other->equals($one));
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function hashCodeProvider(): array
    {
        return [
            [Data\Enum1::valueOf('FOO'), Data\Enum1::valueOf('FOO'), true],
            [Data\Enum1::valueOf('BAR'), Data\Enum1::valueOf('BAR'), true],
            [Data\Enum1::valueOf('BAZ'), Data\Enum1::valueOf('BAZ'), true],
            [Data\Enum1::valueOf('FOO'), Data\Enum1::valueOf('BAR'), false],
            [Data\Enum1::valueOf('BAR'), Data\Enum1::valueOf('BAZ'), false],
            [Data\Enum1::valueOf('BAZ'), Data\Enum1::valueOf('FOO'), false],
        ];
    }

    /**
     * @dataProvider hashCodeProvider
     */
    public function testHashCode(Enum $one, Enum $other, bool $expected): void
    {
        $this->assertSame($expected, $one->hashCode() === $other->hashCode());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function getValueProvider(): array
    {
        return [
            [Data\Enum2::valueOf('FOO'), 1],
            [Data\Enum2::valueOf('BAR'), 2],
            [Data\Enum2::valueOf('BAZ'), 3],
        ];
    }

    /**
     * @param mixed $expected
     * @dataProvider getValueProvider
     */
    public function testGetValue(Data\Enum2 $enum, $expected): void
    {
        $this->assertSame($expected, $enum->getValue());
    }
}
