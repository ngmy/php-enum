<?php

declare(strict_types=1);

namespace Ngmy\Enum;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Ngmy\TypedArray\TypedArray;
use Traversable;

/**
 * @implements ArrayAccess<Enum, mixed>
 * @implements IteratorAggregate<string, mixed>
 * @see https://www.php.net/manual/en/class.arrayaccess.php
 * @see https://www.php.net/manual/en/class.countable.php
 * @see https://www.php.net/manual/en/class.iteratoraggregate.php
 *
 * @phpstan-template TKey
 * @phpstan-template TValue
 * @phpstan-implements ArrayAccess<TKey, TValue>
 * @phpstan-implements IteratorAggregate<string, TValue>
 */
class EnumMap implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var TypedArray<Enum, mixed>
     * @phpstan-var TypedArray<TKey, TValue>
     */
    private $typedArray;
    /**
     * @var array<int, Enum>
     * @phpstan-var array<int, TKey>
     */
    private $keys = [];

    /**
     * Creates a new instance of the enum map with the specified enum type key.
     *
     * @return EnumMap<Enum, mixed>
     *
     * @phpstan-template TEnum
     * @phpstan-param class-string<TEnum> $class
     * @return EnumMap<TEnum, mixed>
     */
    public static function new(string $class): self
    {
        self::validateEnum($class);
        $enumMap = new self(TypedArray::new()->withClassKey($class));
        return $enumMap;
    }

    /**
     * Returns a new instance of the enum map with the array type value.
     *
     * @return EnumMap<Enum, array<int|string, mixed>>
     *
     * @phpstan-return EnumMap<TKey, array<int|string, mixed>>
     */
    public function withArrayValue(): self
    {
        return new self($this->typedArray->withArrayValue());
    }

    /**
     * Returns a new instance of the enum map with the bool type value.
     *
     * @return EnumMap<Enum, bool>
     *
     * @phpstan-return EnumMap<TKey, bool>
     */
    public function withBoolValue(): self
    {
        return new self($this->typedArray->withBoolValue());
    }

    /**
     * Returns a new instance of the enum map with the float type value.
     *
     * @return EnumMap<Enum, float>
     *
     * @phpstan-return EnumMap<TKey, float>
     */
    public function withFloatValue(): self
    {
        return new self($this->typedArray->withFloatValue());
    }

    /**
     * Returns a new instance of the enum map with the int type value.
     *
     * @return EnumMap<Enum, int>
     *
     * @phpstan-return EnumMap<TKey, int>
     */
    public function withIntValue(): self
    {
        return new self($this->typedArray->withIntValue());
    }

    /**
     * Returns a new instance of the enum map with the mixed type value.
     *
     * @return EnumMap<Enum, mixed>
     *
     * @phpstan-return EnumMap<TKey, mixed>
     */
    public function withMixedValue(): self
    {
        return new self($this->typedArray->withMixedValue());
    }

    /**
     * Returns a new instance of the enum map with the object type value.
     *
     * @return EnumMap<Enum, object>
     *
     * @phpstan-return EnumMap<TKey, object>
     */
    public function withObjectValue(): self
    {
        return new self($this->typedArray->withObjectValue());
    }

    /**
     * Returns a new instance of the enum map with the resource type value.
     *
     * @return EnumMap<Enum, resource>
     *
     * @phpstan-return EnumMap<TKey, resource>
     */
    public function withResourceValue(): self
    {
        return new self($this->typedArray->withResourceValue());
    }

    /**
     * Returns a new instance of the enum map with the string type value.
     *
     * @return EnumMap<Enum, string>
     *
     * @phpstan-return EnumMap<TKey, string>
     */
    public function withStringValue(): self
    {
        return new self($this->typedArray->withStringValue());
    }

    /**
     * Returns a new instance of the enum map with the specified class type value.
     *
     * @return EnumMap<Enum, object>
     *
     * @phpstan-template TClass
     * @phpstan-param class-string<TClass> $class
     * @phpstan-return EnumMap<TKey, TClass>
     */
    public function withClassValue(string $class): self
    {
        return new self($this->typedArray->withClassValue($class));
    }

    /**
     * Returns a new instance of the enum map with the class type value that implements the specified interface.
     *
     * @return EnumMap<Enum, object>
     *
     * @phpstan-template TInterface
     * @phpstan-param class-string<TInterface> $interface
     * @phpstan-return EnumMap<TKey, TInterface>
     */
    public function withInterfaceValue(string $interface): self
    {
        return new self($this->typedArray->withInterfaceValue($interface));
    }

    /**
     * Returns a new instance of the enum map with the class type value that uses the specified trait.
     *
     * @return EnumMap<Enum, object>
     *
     * @phpstan-param class-string $trait
     * @phpstan-return EnumMap<TKey, object>
     */
    public function withTraitValue(string $trait): self
    {
        return new self($this->typedArray->withTraitValue($trait));
    }

    /**
     * Determines if the enum map is empty or not.
     */
    public function isEmpty(): bool
    {
        return $this->typedArray->isEmpty();
    }

    /**
     * Gets the enum map of values as a plain array.
     *
     * @return array<string, object>
     *
     * @phpstan-return array<string, TValue>
     */
    public function toArray(): array
    {
        $array = $this->typedArray->toArray();
        return \array_reduce(\array_keys($array), function (array $carry, int $hashCode) use ($array): array {
            $key = $this->keys[$hashCode];
            $carry[$key->name()] = $array[$hashCode];
            return $carry;
        }, []);
    }

    /**
     * @param mixed $key
     * @see https://www.php.net/manual/en/arrayaccess.offsetexists.php
     */
    public function offsetExists($key): bool
    {
        return isset($this->typedArray[$key]);
    }

    /**
     * @param mixed $key
     * @return mixed|null
     * @see https://www.php.net/manual/en/arrayaccess.offsetget.php
     *
     * @phpstan-return TValue|null
     */
    public function offsetGet($key)
    {
        return $this->typedArray[$key];
    }

    /**
     * @param Enum  $key
     * @param mixed $value
     * @see https://www.php.net/manual/en/arrayaccess.offsetset.php
     *
     * @phpstan-param TKey   $key
     * @phpstan-param TValue $value
     */
    public function offsetSet($key, $value): void
    {
        $this->typedArray[$key] = $value;
        $this->keys[$key->hashCode()] = $key;
    }

    /**
     * @param mixed $key
     * @see https://www.php.net/manual/en/arrayaccess.offsetunset.php
     */
    public function offsetUnset($key): void
    {
        unset($this->typedArray[$key]);
        unset($this->keys[$key->hashCode()]);
    }

    /**
     * @see https://www.php.net/manual/en/countable.count.php
     */
    public function count(): int
    {
        return \count($this->typedArray);
    }

    /**
     * @return Traversable<string, mixed>
     * @see https://www.php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @phpstan-return Traversable<string, TValue>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * @phpstan-param class-string $class
     */
    private static function validateEnum(string $class): void
    {
        if (!\class_exists($class) || \get_parent_class($class) != Enum::class) {
            throw new InvalidArgumentException(
                \sprintf('The type of the key must be the concrete enum class, "%s" given.', $class)
            );
        };
    }

    /**
     * @param TypedArray<Enum, mixed> $typedArray
     *
     * @phpstan-param TypedArray<TKey, TValue> $typedArray
     */
    private function __construct(TypedArray $typedArray)
    {
        $this->typedArray = $typedArray;
    }
}
