<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\Enum\Enum;
use Ngmy\Enum\EnumSet;
use stdClass;

class EnumSetTest extends TestCase
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function allOfProvider(): array
    {
        return [
            [Data\Enum1::class, [
                0 => Data\Enum1::valueOf('FOO'),
                1 => Data\Enum1::valueOf('BAR'),
                2 => Data\Enum1::valueOf('BAZ'),
            ]],
            [Data\Enum5::class, new InvalidArgumentException()],
            [stdClass::class, new InvalidArgumentException()],
        ];
    }

    /**
     * @param array<string, object>|Exception $expected
     * @dataProvider allOfProvider
     *
     * @phpstan-param class-string $class
     */
    public function testAllOf(string $class, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        $actual = EnumSet::allOf($class);
        $this->assertEquals($expected, $actual->toArray());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function noneOfProvider(): array
    {
        return [
            [Data\Enum1::class, []],
            [Data\Enum5::class, new InvalidArgumentException()],
            [stdClass::class, new InvalidArgumentException()],
        ];
    }

    /**
     * @param array<string, object>|Exception $expected
     * @dataProvider noneOfProvider
     *
     * @phpstan-param class-string $class
     */
    public function testNoneOf(string $class, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        $actual = EnumSet::noneOf($class);
        $this->assertEquals($expected, $actual->toArray());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function ofProvider(): array
    {
        return [
            [Data\Enum1::valueOf('FOO'), [
                0 => Data\Enum1::valueOf('FOO'),
            ]],
        ];
    }

    /**
     * @param array<string, object>|Exception $expected
     * @dataProvider ofProvider
     */
    public function testOf(Enum $class, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        $actual = EnumSet::of($class);
        $this->assertEquals($expected, $actual->toArray());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function rangeProvider(): array
    {
        return [
            [Data\Enum1::valueOf('FOO'), Data\Enum1::valueOf('BAZ'), [
                0 => Data\Enum1::valueOf('FOO'),
                1 => Data\Enum1::valueOf('BAR'),
                2 => Data\Enum1::valueOf('BAZ'),
            ]],
            [Data\Enum1::valueOf('FOO'), Data\Enum1::valueOf('BAR'), [
                0 => Data\Enum1::valueOf('FOO'),
                1 => Data\Enum1::valueOf('BAR'),
            ]],
            [Data\Enum1::valueOf('BAR'), Data\Enum1::valueOf('BAZ'), [
                0 => Data\Enum1::valueOf('BAR'),
                1 => Data\Enum1::valueOf('BAZ'),
            ]],
            [Data\Enum1::valueOf('FOO'), Data\Enum1::valueOf('FOO'), [
                0 => Data\Enum1::valueOf('FOO'),
            ]],
            [Data\Enum1::valueOf('BAR'), Data\Enum1::valueOf('BAR'), [
                0 => Data\Enum1::valueOf('BAR'),
            ]],
            [Data\Enum1::valueOf('BAZ'), Data\Enum1::valueOf('BAZ'), [
                0 => Data\Enum1::valueOf('BAZ'),
            ]],
            [Data\Enum1::valueOf('BAR'), Data\Enum1::valueOf('FOO'), new InvalidArgumentException()],
            [Data\Enum1::valueOf('FOO'), Data\Enum2::valueOf('BAZ'), new InvalidArgumentException()],
         ];
    }

    /**
     * @param array<string, object>|Exception $expected
     * @dataProvider rangeProvider
     */
    public function testRange(Enum $from, Enum $to, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        $actual = EnumSet::range($from, $to);
        $this->assertEquals($expected, $actual->toArray());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function isEmptyProvider(): array
    {
        return [
            [EnumSet::noneOf(Data\Enum1::class), true],
            [EnumSet::allOf(Data\Enum1::class), false]
        ];
    }

    /**
     * @param EnumSet<Enum> $enumSet
     * @dataProvider isEmptyProvider
     */
    public function testIsEmpty(EnumSet $enumSet, bool $expected): void
    {
        $this->assertSame($expected, $enumSet->isEmpty());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function countProvider(): array
    {
        return [
            [EnumSet::noneOf(Data\Enum1::class), 0],
            [EnumSet::allOf(Data\Enum1::class), 3]
        ];
    }

    /**
     * @param EnumSet<Enum> $enumSet
     * @dataProvider countProvider
     */
    public function testCount(EnumSet $enumSet, int $expected): void
    {
        $this->assertSame($expected, $enumSet->count());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function enumSetProvider(): array
    {
        return [
            [
                EnumSet::noneOf(Data\Enum1::class),
                [
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                ],
                [
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                ],
            ],
            [
                EnumSet::noneOf(Data\Enum1::class),
                [
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                ],
                [
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                ],
            ],
            [
                EnumSet::noneOf(Data\Enum1::class),
                [
                    Data\Enum2::FOO(),
                ],
                new InvalidArgumentException(),
            ],
            [
                EnumSet::noneOf(Data\Enum1::class),
                [1],
                new InvalidArgumentException(),
            ],
        ];
    }

    /**
     * TODO: Split this test into several smaller tests
     *
     * @param EnumSet<Enum>                      $enumSet
     * @param array<int|string, mixed>           $enums
     * @param array<int|string, mixed>|Exception $expected
     * @dataProvider enumSetProvider
     */
    public function testEnumSet(EnumSet $enumSet, array $enums, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        foreach ($enums as $enum) {
            $enumSet[] = $enum;
        }
        \assert(\is_array($expected));
        $this->assertEquals($expected, $enumSet->toArray());
        $i = 0;
        foreach ($enumSet as $enum) {
            $this->assertEquals($expected[$i], $enum);
            $this->assertEquals($expected[$i], $enumSet[$enum]);
            $this->assertTrue(isset($enumSet[$enum]));
            unset($enumSet[$enum]);
            $this->assertFalse(isset($enumSet[$enum]));
            ++$i;
        }
    }
}
