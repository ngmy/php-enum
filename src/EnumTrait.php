<?php

declare(strict_types=1);

namespace Ngmy\Enum;

use BadMethodCallException;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;
use ReflectionClass;

trait EnumTrait
{
    /** @var string */
    private $name;

    /**
     * Returns the enum constant of the specified name.
     *
     * @param list<mixed> $arguments
     * @return static
     */
    final public static function __callStatic(string $name, array $arguments): self
    {
        return self::valueOf($name);
    }

    /**
     * Returns the enum constant of the specified name.
     *
     * @return static
     */
    final public static function valueOf(string $name): self
    {
        return new static($name);
    }

    /**
     * Returns all constants of this enum type.
     *
     * @return TypedArray<self>
     */
    final public static function values(): TypedArray
    {
        $items = \array_map(function (string $name): self {
            return self::valueOf($name);
        }, self::names());
        return TypedArray::ofClass(static::class, $items);
    }

    /**
     * Returns names of all constants of this enum type.
     *
     * @return list<string>
     * @internal
     */
    final public static function names(): array
    {
        $reflectionClass = new ReflectionClass(\get_called_class());
        return \array_keys($reflectionClass->getStaticProperties());
    }

    /**
     * Returns the name of this enum constant, exactly as declared in its enum declaration.
     */
    final public function name(): string
    {
        return $this->name;
    }

    /**
     * Returns the name of this enum constant, as contained in the declaration.
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Returns the ordinal of this enum constant.
     *
     * Its position in its enum declaration, where the initial constant is assigned an ordinal of zero.
     */
    final public function ordinal(): int
    {
        \assert(\is_int(\array_search($this->name, self::names())));
        return \array_search($this->name, self::names());
    }

    /**
     * Returns true if the specified object is equal to this enum constant.
     */
    final public function equals(object $other): bool
    {
        return $this == $other;
    }

    final public function __wakeup(): void
    {
        throw new BadMethodCallException('You are not allowed to unserialize.');
    }

    /**
     * @param mixed $value
     */
    final public function __set(string $property, $value): void
    {
        throw new BadMethodCallException('You are not allowed to set data.');
    }

    /**
     * @return void
     */
    final protected function __clone()
    {
        throw new BadMethodCallException('You are not allowed to clone.');
    }

    final private function __construct(string $name)
    {
        if (!\in_array($name, self::names())) {
            throw new InvalidArgumentException(\sprintf('The name "%s" is not defined.', $name));
        }
        $this->name = $name;
    }
}
