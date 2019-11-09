<?php

declare(strict_types=1);

namespace HttpClientBinder\Base;

use BadMethodCallException;
use InvalidArgumentException;
use ReflectionClass;

// TODO implementation and test
abstract class Enum
{
    /**
     * @var mixed
     */
    protected $value;

    protected static $cache = [];

    /**
     * @param $name
     * @param $arguments
     * @return static
     */
    public static function __callStatic($name, $arguments): self
    {
        $values = static::toArray();
        if (isset($values[$name]) || array_key_exists($name, $values)) {
            return new static($values[$name]);
        }

        throw new BadMethodCallException(
            sprintf(
                'Unsupported enum value %s. Available %s',
                $name,
                implode(',', array_keys($values[$name]))
            )
        );
    }

    public function toString(): string
    {
        return (string)$this->value;
    }

    public static function toArray(): array
    {
        $class = get_called_class();
        if (!isset(static::$cache[$class])) {
            $reflection = new ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * @param $value
     * @return static
     */
    public static function fromValue($value): self
    {
        $result = array_search($value, static::toArray());

        if (false === $result) {
            throw new InvalidArgumentException(sprintf(
                "Unsupported value for enum %s available values [%s]",
                get_called_class(),
                implode(static::toArray())
            ));
        }

        return new static($result);
    }

    final private function __construct($value)
    {
        $this->value = $value;
    }
}