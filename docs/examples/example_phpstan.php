<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

PHPStan\dumpType(Ngmy\Enum\Tests\Data\Enum1::FOO()); // Ngmy\Enum\Tests\Data\Enum1

$enumMap = Ngmy\Enum\EnumMap::new(Ngmy\Enum\Tests\Data\Enum1::class);
PHPStan\dumpType($enumMap); // Ngmy\Enum\EnumMap<Ngmy\Enum\Tests\Data\Enum1, mixed>
$enumMap[Ngmy\Enum\Tests\Data\Enum1::FOO()] = 1; // Good
$enumMap[Ngmy\Enum\Tests\Data\Enum2::FOO()] = 1; // No good

$enumSet = Ngmy\Enum\EnumSet::noneOf(Ngmy\Enum\Tests\Data\Enum1::class);
PHPStan\dumpType($enumSet); // Ngmy\Enum\EnumSet<Ngmy\Enum\Tests\Data\Enum1>
$enumSet[] = Ngmy\Enum\Tests\Data\Enum1::FOO(); // Good
$enumSet[] = Ngmy\Enum\Tests\Data\Enum2::FOO(); // No good

$enumSet = Ngmy\Enum\EnumSet::range(Ngmy\Enum\Tests\Data\Enum1::FOO(), Ngmy\Enum\Tests\Data\Enum1::BAZ()); // Good
$enumSet = Ngmy\Enum\EnumSet::range(Ngmy\Enum\Tests\Data\Enum1::FOO(), Ngmy\Enum\Tests\Data\Enum2::BAZ()); // Good. This is the false negative
