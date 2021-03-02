<?php

declare(strict_types=1);

namespace Ngmy\Enum\Tests;

use Exception;
use Ngmy\Enum\Enum;
use Ngmy\Enum\EnumMap;

class EnumMapTest extends TestCase
{
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
     * @param EnumMap<Enum, mixed>           $enumMap
     * @param list<Enum>                     $keys
     * @param list<mixed>                    $values
     * @param array<string, mixed>|Exception $expected
     * @dataProvider dataProvider
     */
    public function test(EnumMap $enumMap, array $keys, array $values, $expected): void
    {
        foreach ($keys as $i => $key) {
            $enumMap[$key] = $values[$i];
        }
        $this->assertEquals($expected, $enumMap->toArray());
    }
}
