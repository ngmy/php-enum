<?php

declare(strict_types=1);

namespace Ngmy\Enum;

use BadMethodCallException;
use InvalidArgumentException;
use LogicException;
use ReflectionClass;

abstract class Enum
{
    /**
     * @var array<string, list<string>>
     * @phpstan-var array<class-string, list<string>>
     */
    private static $names = [];

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
     * @return list<static>
     */
    final public static function values(): array
    {
        return \array_map(function (string $name): self {
            return self::valueOf($name);
        }, self::names());
    }

    /**
     * Returns names of all constants of this enum type.
     *
     * @return list<string>
     * @internal
     */
    final public static function names(): array
    {
        self::validateInheritance();
        $class = \get_called_class();
        if (isset(self::$names[$class])) {
            return self::$names[$class];
        }
        self::$names[$class] = [];
        $reflectionClass = new ReflectionClass($class);
        $staticProperties = $reflectionClass->getStaticProperties();
        foreach (\array_keys($staticProperties) as $propertyName) {
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
            $docComment = $reflectionProperty->getDocComment();
            if ($docComment === false) {
                continue;
            }
            $lines = \explode(\PHP_EOL, $docComment);
            foreach ($lines as $line) {
                if (!\preg_match('/@[^\s]+/', $line, $mathces) || $mathces[0] != '@enum') {
                    continue;
                }
                self::$names[$class][] = $propertyName;
            }
        }
        return self::$names[$class];
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

    /**
     * Returns the hash code for this enum constant
     */
    final public function hashCode(): int
    {
        $result = 17;
        $nameLength = \strlen($this->name);
        for ($i = 0; $i < $nameLength; ++$i) {
            $result = 31 * $result + \ord($this->name[$i]);
        }
        return $result;
    }

    /**
     * @param mixed $value
     */
    final public function __set(string $property, $value): void
    {
        throw new BadMethodCallException('You are not allowed to set data.');
    }

    final public function __wakeup(): void
    {
        throw new BadMethodCallException('You are not allowed to unserialize.');
    }

    /**
     * @return void
     */
    final protected function __clone()
    {
        throw new BadMethodCallException('You are not allowed to clone.');
    }

    private static function validateInheritance(): void
    {
        $reflectionClass = new ReflectionClass(\get_called_class());
        while ($reflectionClass = $reflectionClass->getParentClass()) {
            if ($reflectionClass->getName() != self::class) {
                throw new LogicException('You are not allowed to inherit from the concrete enum class.');
            }
        }
    }

    final private function __construct(string $name)
    {
        if (!\in_array($name, self::names())) {
            throw new InvalidArgumentException(\sprintf('The name "%s" is not defined.', $name));
        }
        $this->name = $name;
    }
}
