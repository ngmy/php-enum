<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\Enum\Enum;
use Ngmy\Enum\EnumMap;

class EnumMapTest extends TestCase
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function newProvider(): array
    {
        return [
            [Data\Enum1::class],
            [\stdClass::class, new InvalidArgumentException()],
            ['a', new InvalidArgumentException()],
        ];
    }

    /**
     * @dataProvider newProvider
     *
     * @phpstan-param class-string $class
     */
    public function testNew(string $class, Exception $exception = null): void
    {
        if (\is_null($exception)) {
            $this->expectNotToPerformAssertions();
        }
        if ($exception instanceof Exception) {
            $this->expectException(\get_class($exception));
        }
        // NOTE: To test that an exception is thrown
        // @phpstan-ignore-next-line
        EnumMap::new($class);
    }

    /**
     * TODO: Also test setting the value
     */
    public function testWith(): void
    {
        $enumMap = EnumMap::new(Data\Enum1::class);
        $this->assertInstanceOf(EnumMap::class, $enumMap->withArrayValue());
        $this->assertInstanceOf(EnumMap::class, $enumMap->withBoolValue());
        $this->assertInstanceOf(EnumMap::class, $enumMap->withFloatValue());
        $this->assertInstanceOf(EnumMap::class, $enumMap->withIntValue());
        $this->assertInstanceOf(EnumMap::class, $enumMap->withMixedValue());
        $this->assertInstanceOf(EnumMap::class, $enumMap->withObjectValue());
        $this->assertInstanceOf(EnumMap::class, $enumMap->withResourceValue());
        $this->assertInstanceOf(EnumMap::class, $enumMap->withStringValue());
        $this->assertInstanceOf(EnumMap::class, $enumMap->withClassValue(Data\Enum1::class));
        $this->assertInstanceOf(EnumMap::class, $enumMap->withInterfaceValue(Data\Interface1::class));
        $this->assertInstanceOf(EnumMap::class, $enumMap->withTraitValue(Data\Trait1::class));
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function isEmptyProvider(): array
    {
        return [
            [[], true],
            [[Data\Enum1::valueOf('FOO')], false]
        ];
    }

    /**
     * @param array<int, Data\Enum1> $enums
     * @dataProvider isEmptyProvider
     *
     * @phpstan-param list<Data\Enum1> $enums
     */
    public function testIsEmpty(array $enums, bool $expected): void
    {
        $enumMap = EnumMap::new(Data\Enum1::class);
        foreach ($enums as $enum) {
            $enumMap[$enum] = $enum;
        }
        $this->assertSame($expected, $enumMap->isEmpty());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function countProvider(): array
    {
        return [
            [[], 0],
            [[Data\Enum1::valueOf('FOO')], 1]
        ];
    }

    /**
     * @param array<int, Data\Enum1> $enums
     * @dataProvider countProvider
     *
     * @phpstan-param list<Data\Enum1> $enums
     */
    public function testCount(array $enums, int $expected): void
    {
        $enumMap = EnumMap::new(Data\Enum1::class);
        foreach ($enums as $enum) {
            $enumMap[$enum] = $enum;
        }
        $this->assertSame($expected, $enumMap->count());
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function dataProvider(): array
    {
        $resource1 = \tmpfile();
        $resource2 = \tmpfile();
        $resource3 = \tmpfile();

        return [
            [
                EnumMap::new(Data\Enum1::class)->withArrayValue(),
                [
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                ],
                [
                    [0],
                    [1],
                    [2],
                ],
                [
                    'FOO' => [0],
                    'BAR' => [1],
                    'BAZ' => [2],
                ],
            ],
            [
                EnumMap::new(Data\Enum1::class)->withBoolValue(),
                [
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                ],
                [
                    true,
                    false,
                    true,
                ],
                [
                    'FOO' => true,
                    'BAR' => false,
                    'BAZ' => true,
                ],
            ],
            [
                EnumMap::new(Data\Enum1::class)->withFloatValue(),
                [
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                ],
                [
                    0.0,
                    0.1,
                    0.2,
                ],
                [
                    'FOO' => 0.0,
                    'BAR' => 0.1,
                    'BAZ' => 0.2,
                ],
            ],
            [
                EnumMap::new(Data\Enum1::class)->withIntValue(),
                [
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                ],
                [
                    0,
                    1,
                    2,
                ],
                [
                    'FOO' => 0,
                    'BAR' => 1,
                    'BAZ' => 2,
                ],
            ],
            [
                EnumMap::new(Data\Enum1::class)->withResourceValue(),
                [
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                ],
                [
                    $resource1,
                    $resource2,
                    $resource3,
                ],
                [
                    'FOO' => $resource1,
                    'BAR' => $resource2,
                    'BAZ' => $resource3,
                ],
            ],
            [
                EnumMap::new(Data\Enum1::class)->withStringValue(),
                [
                    Data\Enum1::FOO(),
                    Data\Enum1::BAR(),
                    Data\Enum1::BAZ(),
                ],
                [
                    '0',
                    '1',
                    '2',
                ],
                [
                    'FOO' => '0',
                    'BAR' => '1',
                    'BAZ' => '2',
                ],
            ],
        ];
    }

    /**
     * TODO: Split this test into several smaller tests
     *
     * @param EnumMap<Enum, mixed>           $enumMap
     * @param array<int, Enum>               $keys
     * @param array<int, mixed>              $values
     * @param array<string, mixed>|Exception $expected
     * @dataProvider dataProvider
     *
     * @phpstan-param list<Enum>  $keys
     * @phpstan-param list<mixed> $values
     */
    public function test(EnumMap $enumMap, array $keys, array $values, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }
        foreach ($keys as $i => $key) {
            $enumMap[$key] = $values[$i];
        }
        \assert(\is_array($expected));
        $this->assertEquals($expected, $enumMap->toArray());
        $i = 0;
        foreach ($enumMap as $name => $value) {
            $this->assertEquals($expected[$name], $value);
            $this->assertEquals($expected[$name], $enumMap[$keys[$i]]);
            $this->assertTrue(isset($enumMap[$keys[$i]]));
            unset($enumMap[$keys[$i]]);
            $this->assertFalse(isset($enumMap[$keys[$i]]));
            ++$i;
        }
    }
}
